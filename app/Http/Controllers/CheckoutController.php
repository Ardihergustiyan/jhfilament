<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // public function index()
    // {
    //     // Ambil user yang sedang login
    //     $user = auth()->user();

    //     // Ambil item keranjang beserta produk dan varian
    //     $cartItems = Cart::select('carts.*', 'products.name as product_name', 'variants.main_image', 'variants.color')
    //         ->join('products', 'carts.product_id', '=', 'products.id')
    //         ->leftJoinSub(
    //             DB::table('product_variants')
    //                 ->select('id as variant_id', 'color', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(image, "$[0]")) as main_image'))
    //                 ->groupBy('id'),
    //             'variants',
    //             'carts.variant_id',
    //             '=',
    //             'variants.variant_id'
    //         )
    //         ->where('carts.user_id', $user->id)
    //         ->get();

    //     $subtotal = session('subtotal', 0);
    //     $voucherAmount = session('voucherAmount', 0);
    //     $total = session('total', 0);

    //     return view('checkout', compact('cartItems', 'subtotal', 'voucherAmount', 'total'));
    // }
    public function index()
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil item keranjang beserta produk dan varian
        // Ambil item keranjang beserta produk dan varian
        $cartItems = Cart::select('carts.*', 'products.name as product_name', 'products.image as product_image', 'variants.main_image', 'variants.color')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->leftJoinSub(
            DB::table('product_variants')
                ->select('id as variant_id', 'color', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(image, "$[0]")) as main_image'))
                ->groupBy('id'),
            'variants',
            'carts.variant_id',
            '=',
            'variants.variant_id'
        )
        ->where('carts.user_id', $user->id)
        ->get();

        // Hitung ulang subtotal, voucherAmount, dan total
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->product->het_price ?? 0) * ($item->quantity ?? 0);
        });

        $voucherId = $cartItems->first()->voucher_id ?? null;
        $voucherAmount = 0;

        if ($voucherId) {
            $voucher = Voucher::find($voucherId);
            if ($voucher) {
                $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;
            }
        }

        $total = $subtotal - $voucherAmount;

        return view('checkout', compact('cartItems', 'subtotal', 'voucherAmount', 'total'));
    }

    public function store(Request $request)
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil item keranjang
        $cartItems = Cart::where('user_id', $user->id)->get();

        // Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->product->het_price ?? 0) * ($item->quantity ?? 0);
        });

        // Ambil voucher_id dari keranjang
        $voucherId = $cartItems->first()->voucher_id ?? null;
        $voucherAmount = 0;

        if ($voucherId) {
            $voucher = Voucher::find($voucherId);

            if ($voucher) {
                $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;
            }
        }

        // Hitung total
        $total = $subtotal - $voucherAmount;

        // Buat order
        $order = Order::create([
            'user_id' => $user->id,
            'status_id' => 1, // Misalnya, status_id 1 adalah 'Pending'
            'total_price' => $total,
            'discount_amount' => $voucherAmount,
            'shipping_method' => $request->shipping_method,
            'notes' => $request->notes,
            'voucher_id' => $voucherId,
        ]);

        // Hapus item keranjang setelah order dibuat
        Cart::where('user_id', $user->id)->delete();

        // Redirect ke halaman sukses atau pembayaran
        return redirect()->route('payment.success');
    }

    // public function processCheckout(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'payment-method' => 'required|string',
    //         'delivery-method' => 'required|string',
    //         'notes' => 'nullable|string',
    //     ]);

    //     // Ambil user yang sedang login
    //     $user = auth()->user();

    //     // Ambil item keranjang
    //     $cartItems = Cart::with(['product', 'variant'])
    //         ->where('user_id', $user->id)
    //         ->get();

    //     // Jika keranjang kosong, kembalikan ke halaman cart dengan pesan error
    //     if ($cartItems->isEmpty()) {
    //         return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
    //     }

    //     // Hitung subtotal
    //     $subtotal = $cartItems->sum(function ($item) {
    //         return ($item->product->het_price ?? 0) * ($item->quantity ?? 0);
    //     });

    //     // Ambil voucher_id dari keranjang
    //     $voucherId = $cartItems->first()->voucher_id ?? null;
    //     $voucherAmount = 0;

    //     if ($voucherId) {
    //         // Cari voucher berdasarkan voucher_id
    //         $voucher = Voucher::where('id', $voucherId)
    //             ->where('status', 'Unused') // Pastikan status voucher masih Unused
    //             ->where('start_date', '<=', now())
    //             ->where('end_date', '>=', now())
    //             ->first();
    
    //         if ($voucher) {
    //             // Hitung voucherAmount berdasarkan persentase diskon
    //             $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;
    
    //             // Update used_by_user_id pada tabel vouchers
    //             $voucher->update([
    //                 'applicable_id' => $user->id, // Simpan ID user yang menggunakan voucher
    //                 'status' => 'Used', // Update status voucher menjadi Used
    //             ]);
    //         } else {
    //             // Jika voucher tidak valid, hapus voucher_id dari keranjang
    //             Cart::where('user_id', $user->id)->update(['voucher_id' => null]);
    //         }
    //     }
    //     // Hitung total
    //     $total = $subtotal - $voucherAmount;

    //     // Buat order baru
    //     $order = Order::create([
    //         'user_id' => $user->id,
    //         'status_id' => OrderStatus::where('slug', 'diproses')->first()->id,
    //         'total_price' => $total,
    //         'discount_amount' => $voucherAmount, // Simpan discount_amount
    //         'shipping_method' => $request->input('delivery-method'),
    //         'notes' => $request->input('notes'),
    //         'voucher_id' => $voucherId, // Simpan voucher_id
    //     ]);

    //     // Simpan order items
    //     foreach ($cartItems as $item) {
    //         OrderItem::create([
    //             'order_id' => $order->id,
    //             'product_id' => $item->product_id,
    //             'product_variant_id' => $item->variant_id,
    //             'quantity' => $item->quantity,
    //             'unit_price' => $item->product->het_price,
    //             'total_price' => $item->product->het_price * $item->quantity,
    //         ]);
    //     }

    //     // Buat payment record
    //     Payment::create([
    //         'order_id' => $order->id,
    //         'payment_method' => $request->input('payment-method'),
    //         'payment_status' => 'pending',
    //         'transaction_id' => null, // Isi dengan transaction ID jika menggunakan payment gateway
    //     ]);

    //     // Hapus cart items setelah checkout
    //     Cart::where('user_id', $user->id)->delete();

    //     // Update status voucher menjadi 'Used' jika voucher digunakan
    //     // if ($voucherId) {
    //     //     Voucher::where('id', $voucherId)->update(['status' => 'Used']);
    //     // }

      

    //     // Redirect ke halaman terima kasih atau halaman lainnya
    //     return redirect()->route('order.thankyou', ['order_id' => $order->id])
    //             ->with('success', 'Order berhasil dibuat!');
    // }
    public function processCheckout(Request $request)
    {
        // Validasi input
        $request->validate([
            'payment-method' => 'required|string',
            'delivery-method' => 'required|string',
            'phone_number' => 'required|string|max:15', // Validasi nomor HP
            'notes' => 'nullable|string',
        ]);

        // Ambil user yang sedang login
        $user = auth()->user();

        // Update nomor HP jika diubah
        if ($request->phone_number !== $user->phone_number) {
            $user->update(['phone_number' => $request->phone_number]);
        }

        // Ambil item keranjang
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->get();

        // Jika keranjang kosong, kembalikan ke halaman cart dengan pesan error
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->product->het_price ?? 0) * ($item->quantity ?? 0);
        });

        // Ambil voucher_id dari keranjang
        $voucherId = $cartItems->first()->voucher_id ?? null;
        $voucherAmount = 0;

        if ($voucherId) {
            // Cari voucher berdasarkan voucher_id
            $voucher = Voucher::where('id', $voucherId)
                ->where('status', 'Unused') // Pastikan status voucher masih Unused
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($voucher) {
                // Periksa apakah user sudah pernah menggunakan voucher ini sebelumnya
                $hasUsedVoucher = Order::where('user_id', $user->id)
                                    ->where('voucher_id', $voucher->id)
                                    ->exists();

                if ($hasUsedVoucher) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah menggunakan voucher ini sebelumnya.'
                    ]);
                }

                // Hitung voucherAmount berdasarkan persentase diskon
                $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;

                // Update applicable_id pada tabel vouchers
                // $voucher->update([
                //     'applicable_id' => $user->id, // Simpan ID user yang menggunakan voucher
                //     'status' => 'Used', // Update status voucher menjadi Used
                // ]);
            } else {
                // Jika voucher tidak valid, hapus voucher_id dari keranjang
                Cart::where('user_id', $user->id)->update(['voucher_id' => null]);

                return response()->json([
                    'success' => false,
                    'message' => 'Voucher tidak valid atau sudah digunakan.'
                ]);
            }
        }

        // Hitung total
        $total = $subtotal - $voucherAmount;

        // Buat order baru
        $order = Order::create([
            'user_id' => $user->id,
            'status_id' => OrderStatus::where('slug', 'diproses')->first()->id,
            'total_price' => $total,
            'discount_amount' => $voucherAmount, // Simpan discount_amount
            'shipping_method' => $request->input('delivery-method'),
            'notes' => $request->input('notes'),
            'voucher_id' => $voucherId, // Simpan voucher_id
        ]);

        // Simpan order items dan kurangi stok
        foreach ($cartItems as $item) {
            // Simpan order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->het_price,
                'total_price' => $item->product->het_price * $item->quantity,
            ]);

            // Kurangi stok
            if ($item->variant_id) {
                // Jika produk memiliki varian, kurangi stok pada tabel product_variant
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    // Pengecekan stok
                    if ($variant->stock < $item->quantity) {
                        return redirect()->route('cart')->with('error', 'Stok varian ' . $variant->color . ' tidak mencukupi.');
                    }
                    $variant->decrement('stock', $item->quantity);
                }
            } else {
                // Jika produk tidak memiliki varian, kurangi stok pada tabel product
                $product = Product::find($item->product_id);
                if ($product) {
                    // Pengecekan stok
                    if ($product->stock < $item->quantity) {
                        return redirect()->route('cart')->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
                    }
                    $product->decrement('stock', $item->quantity);
                }
            }
        }


        // Buat payment record
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->input('payment-method'),
            'payment_status' => 'pending',
            'transaction_id' => null, // Isi dengan transaction ID jika menggunakan payment gateway
        ]);

        // Hapus cart items setelah checkout
        Cart::where('user_id', $user->id)->delete();

        // Redirect ke halaman terima kasih atau halaman lainnya
        return redirect()->route('order.thankyou', ['order_id' => $order->id])
                ->with('success', 'Order berhasil dibuat!');
    }
}
