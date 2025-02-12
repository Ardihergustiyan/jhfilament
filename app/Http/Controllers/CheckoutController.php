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
    public function index()
    {
        // Ambil user yang sedang login
        $user = auth()->user();
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Ambil item keranjang beserta harga reseller jika ada
        $cartItems = Cart::select(
                'carts.*', 
                'products.name as product_name', 
                'products.image as product_image', 
                'variants.main_image', 
                'variants.color',
                DB::raw('IFNULL(product_prices.price, products.het_price) as final_price') // Gunakan harga reseller atau het_price
            )
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
            })
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
            return ($item->final_price ?? 0) * ($item->quantity ?? 0); // Gunakan final_price
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
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Ambil item keranjang beserta harga reseller jika ada
        $cartItems = Cart::select(
                'carts.*', 
                DB::raw('IFNULL(product_prices.price, products.het_price) as final_price')
            )
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
            })
            ->where('carts.user_id', $user->id)
            ->get();

        // Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->final_price ?? 0) * ($item->quantity ?? 0); // Gunakan final_price
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

        // Simpan order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->final_price, // Gunakan final_price
                'total_price' => $item->final_price * $item->quantity, // Gunakan final_price
            ]);
        }

        // Hapus item keranjang setelah order dibuat
        Cart::where('user_id', $user->id)->delete();

        // Redirect ke halaman sukses atau pembayaran
        return redirect()->route('payment.success');
    }

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
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Update nomor HP jika diubah
        if ($request->phone_number !== $user->phone_number) {
            $user->update(['phone_number' => $request->phone_number]);
        }

        // Ambil item keranjang beserta harga reseller jika ada
        $cartItems = Cart::select(
                'carts.*', 
                DB::raw('IFNULL(product_prices.price, products.het_price) as final_price')
            )
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
            })
            ->where('carts.user_id', $user->id)
            ->get();

        // Jika keranjang kosong, kembalikan ke halaman cart dengan pesan error
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->final_price ?? 0) * ($item->quantity ?? 0); // Gunakan final_price
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
            'discount_amount' => $voucherAmount,
            'shipping_method' => $request->input('delivery-method'),
            'notes' => $request->input('notes'),
            'voucher_id' => $voucherId,
        ]);

        // Simpan order items dan kurangi stok
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->final_price, // Gunakan final_price
                'total_price' => $item->final_price * $item->quantity, // Gunakan final_price
            ]);

            // Kurangi stok
            if ($item->variant_id) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    if ($variant->stock < $item->quantity) {
                        return redirect()->route('cart')->with('error', 'Stok varian ' . $variant->color . ' tidak mencukupi.');
                    }
                    $variant->decrement('stock', $item->quantity);
                }
            } else {
                $product = Product::find($item->product_id);
                if ($product) {
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
            'transaction_id' => null,
        ]);

        // Hapus cart items setelah checkout
        Cart::where('user_id', $user->id)->delete();

        // Redirect ke halaman terima kasih
        return redirect()->route('order.thankyou', ['order_id' => $order->id])
                ->with('success', 'Order berhasil dibuat!');
    }
}
