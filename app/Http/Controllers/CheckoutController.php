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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        // Ambil user yang sedang login
        $user = auth()->user();
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Ambil cart item IDs dan voucher ID dari session
        $cartItemIds = session('cartItemIds', []);
        $voucherId = session('voucherId', null);

        // Debug session
        Log::info('Cart Item IDs:', $cartItemIds);
        Log::info('Voucher ID:', [$voucherId]);

        // Jika cartItemIds kosong, redirect kembali ke cart
        if (empty($cartItemIds)) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }

        // Ambil item keranjang beserta harga reseller jika ada
        $cartItems = Cart::select(
                'carts.id',
                'carts.user_id',
                'carts.product_id',
                'carts.variant_id',
                'carts.quantity',
                'carts.created_at',
                'carts.updated_at',
                'products.name as product_name',
                'products.image as product_image',
                DB::raw('MAX(variants.main_image) as main_image'),
                DB::raw('IFNULL(product_prices.price, products.het_price) as final_price')
            )
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
            })
            ->leftJoin(DB::raw('(SELECT id AS variant_id, JSON_UNQUOTE(JSON_EXTRACT(image, "$[0]")) AS main_image FROM product_variants) AS variants'), 'carts.variant_id', '=', 'variants.variant_id')
            ->where('carts.user_id', '=', $user->id)
            ->groupBy(
                'carts.id',
                'carts.user_id',
                'carts.product_id',
                'carts.variant_id',
                'carts.quantity',
                'carts.created_at',
                'carts.updated_at',
                'products.name',
                'products.image',
                'product_prices.price',
                'products.het_price'
            )
            ->get();

        // Hitung diskon untuk setiap item di keranjang
        foreach ($cartItems as $cartItem) {
            $discounts = $this->getApplicableDiscounts($cartItem->product_id, $cartItem->product->category_id);
            $cartItem->discounted_price = $this->calculateDiscountedPrice($cartItem->final_price, $discounts);
        }

        // Hitung ulang subtotal, voucherAmount, dan total
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->discounted_price ?? $item->final_price ?? 0) * ($item->quantity ?? 0);
        });

        $voucherAmount = 0;

        if ($voucherId) {
            $voucher = Voucher::find($voucherId);
            if ($voucher) {
                $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;
            }
        }

        $total = $subtotal - $voucherAmount;

        // Generate snap token
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $itemDetails = [];
        foreach ($cartItems as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => intval(round($item->discounted_price ?? $item->final_price)),
                'quantity' => $item->quantity,
                'name' => $item->product_name,
            ];
        }

        $params = array(
            'transaction_details' => array(
                'order_id' => 'ORDER-' . uniqid(), // Anda bisa menggunakan ID yang sesuai
                'gross_amount' => $total,
            ),
            'customer_details' => array(
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone_number,
            ),
            'item_details' => $itemDetails,
        );
        // Validasi gross_amount
        if ($total <= 0) {
            return redirect()->route('cart')->with('error', 'Total pembayaran tidak valid. Silakan tambahkan item ke keranjang.');
        }

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        // try {
        //     $snapToken = \Midtrans\Snap::getSnapToken($params);
        //     dd($snapToken); // Debugging dengan dump and die
        // } catch (\Exception $e) {
        //     dd($e->getMessage()); // Debugging error jika terjadi
        //     return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        // }

        return view('checkout', compact('cartItems', 'subtotal', 'voucherAmount', 'total', 'snapToken'));
    }

    public function store(Request $request)
    {
        // Ambil user yang sedang login
        $user = auth()->user();
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Ambil cart item IDs dan voucher ID dari session
        $cartItemIds = session('cartItemIds', []);
        $voucherId = session('voucherId', null);

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
            ->whereIn('carts.id', $cartItemIds) // Ambil hanya cart items yang dipilih
            ->where('carts.user_id', $user->id)
            ->get();

        // Hitung diskon untuk setiap item di keranjang
        foreach ($cartItems as $cartItem) {
            $discounts = $this->getApplicableDiscounts($cartItem->product_id, $cartItem->product->category_id);
            $cartItem->discounted_price = $this->calculateDiscountedPrice($cartItem->final_price, $discounts);
        }

        // Hitung ulang subtotal, voucherAmount, dan total
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->discounted_price ?? $item->final_price ?? 0) * ($item->quantity ?? 0);
        });

        $voucherAmount = 0;

        if ($voucherId) {
            $voucher = Voucher::find($voucherId);
            if ($voucher) {
                $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;
            }
        }

        $total = $subtotal - $voucherAmount;

        // Validasi stok sebelum membuat pesanan
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            if ($item->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
            }
        }

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
                'unit_price' => $item->discounted_price ?? $item->final_price, // Gunakan harga yang sudah dihitung ulang
                'total_price' => ($item->discounted_price ?? $item->final_price) * $item->quantity,
            ]);

            // Kurangi stok produk
            $product = Product::find($item->product_id);
            $product->stock -= $item->quantity;
            $product->save();
        }

        Cart::whereIn('id', $cartItemIds)->delete();

        // Hapus session cartItemIds dan voucherId setelah order berhasil dibuat
        session()->forget(['cartItemIds', 'voucherId']);

        // Redirect ke halaman sukses atau pembayaran
        return redirect()->route('payment.success');
    }

    public function processCheckout(Request $request)
    {
        // Mulai transaction
        DB::beginTransaction();
    
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'payment-method' => 'required|string',
                'delivery-method' => 'required|string',
                'phone_number' => [
                    'required',
                    'string',
                    'regex:/^(\+62|62|0)[0-9]{9,13}$/', // Hanya angka, mulai dengan +62, 62, atau 0, panjang 10-14 digit
                ],
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator) // Kirim error validasi ke view
                    ->withInput(); // Pertahankan input yang sudah diisi
            }
    
            // Ambil user yang sedang login
            $user = auth()->user();
            $resellerLevelId = $user->reseller_level_id ?? null;
    
            // Update phone_number di tabel users
            $user->phone_number = $request->input('phone_number');
            $user->save();
    
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
    
            // Jika keranjang kosong, kembalikan ke halaman cart dengan pesan error
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
            }
    
            // Periksa stok sebelum melanjutkan
            foreach ($cartItems as $item) {
                if ($item->variant_id) {
                    $variant = ProductVariant::find($item->variant_id);
                    if ($variant && $variant->stock < $item->quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok varian ' . $variant->color . ' tidak mencukupi.',
                        ], 400);
                    }
                } else {
                    $product = Product::find($item->product_id);
                    if ($product && $product->stock < $item->quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok produk ' . $product->name . ' tidak mencukupi.',
                        ], 400);
                    }
                }
            }
    
            // Hitung diskon untuk setiap item di keranjang
            foreach ($cartItems as $cartItem) {
                $discounts = $this->getApplicableDiscounts($cartItem->product_id, $cartItem->product->category_id);
                $cartItem->discounted_price = $this->calculateDiscountedPrice($cartItem->final_price, $discounts);
            }
    
            // Hitung ulang subtotal, voucherAmount, dan total
            $subtotal = $cartItems->sum(function ($item) {
                return ($item->discounted_price ?? $item->final_price ?? 0) * ($item->quantity ?? 0);
            });
    
            // Ambil voucher_id dari keranjang
            $voucherId = $cartItems->first()->voucher_id ?? null;
            $voucherAmount = 0;
    
            if ($voucherId) {
                // Cari voucher berdasarkan voucher_id
                $voucher = Voucher::where('id', $voucherId)
                    ->where('status', 'Unused')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();
    
                if ($voucher) {
                    // Periksa apakah user sudah pernah menggunakan voucher ini sebelumnya
                    $hasUsedVoucher = Order::where('user_id', $user->id)
                                        ->where('voucher_id', $voucher->id)
                                        ->exists();
    
                    if ($hasUsedVoucher) {
                        return redirect()->route('cart')->with('error', 'Anda sudah menggunakan voucher ini sebelumnya.');
                    }
    
                    // Hitung voucherAmount berdasarkan persentase diskon
                    $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;
                } else {
                    // Jika voucher tidak valid, hapus voucher_id dari keranjang
                    Cart::where('user_id', $user->id)->update(['voucher_id' => null]);
                    return redirect()->route('cart')->with('error', 'Voucher tidak valid atau sudah digunakan.');
                }
            }
    
            // Hitung total
            $total = $subtotal - $voucherAmount;
    
            // Validasi gross_amount
            if ($total <= 0) {
                return redirect()->route('cart')->with('error', 'Total pembayaran tidak valid. Silakan tambahkan item ke keranjang.');
            }
    
            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();
    
            // Buat order baru
            $order = Order::create([
                'user_id' => $user->id,
                'status_id' => OrderStatus::where('slug', 'diproses')->first()->id,
                'total_price' => $total,
                'discount_amount' => $voucherAmount,
                'shipping_method' => $request->input('delivery-method'),
                'notes' => $request->input('notes'),
                'voucher_id' => $voucherId,
                'invoice_number' => $invoiceNumber,
            ]);
    
            // Simpan order items dan kurangi stok
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->discounted_price ?? $item->final_price, // Gunakan harga yang sudah dihitung ulang
                    'total_price' => ($item->discounted_price ?? $item->final_price) * $item->quantity,
                ]);
    
                // Kurangi stok
                if ($item->variant_id) {
                    $variant = ProductVariant::find($item->variant_id);
                    if ($variant) {
                        $variant->decrement('stock', $item->quantity);
                    }
                } else {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }
    
            // Buat payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->input('payment-method'),
                'payment_status' => 'pending',
            ]);
    
            // Jika metode pembayaran adalah cash, langsung redirect ke halaman detail pesanan
            if ($request->input('payment-method') === 'cash') {
                // Commit transaction
                DB::commit();
    
                // Hapus cart items setelah checkout
                Cart::where('user_id', $user->id)->delete();
    
                return redirect()->route('order.detail', [
                    'first_name' => $order->user->first_name, // Ambil username dari relasi user
                    'invoice_number' => $order->invoice_number, // Ambil invoice number dari order
                ]);
            }
    
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
    
            // Siapkan item details
            $itemDetails = [];
            foreach ($cartItems as $item) {
                $itemDetails[] = [
                    'id' => $item->product_id, // ID produk
                    'price' => intval(round($item->discounted_price ?? $item->final_price)), // Harga per item
                    'quantity' => $item->quantity, // Jumlah item
                    'name' => $item->product_name, // Nama produk
                ];
            }
    
            $params = array(
                'transaction_details' => array(
                    'order_id' => 'ORDER-' . $order->id,
                    'gross_amount' => $total,
                ),
                'customer_details' => array(
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone_number, // Gunakan phone_number dari user yang sudah diupdate
                ),
                'item_details' => $itemDetails,
                'custom_field1' => $order->invoice_number,
            );
    
            $snapToken = \Midtrans\Snap::getSnapToken($params);
    
            $first_name = $user->first_name;
    
            // Simpan snap token ke kolom transaction_id di tabel payments
            Payment::where('order_id', $order->id)->update(['transaction_id' => $snapToken]);
            
            // Jika transaction_id tidak null, ubah payment_status menjadi 'dibayar'
            Payment::where('order_id', $order->id)
                ->whereNotNull('transaction_id')
                ->update(['payment_status' => 'dibayar']);
                
            // Hapus cart items setelah checkout
            Cart::where('user_id', $user->id)->delete();
    
            // Commit transaction
            DB::commit();
    
            // Redirect ke halaman terima kasih
            // Kembalikan snapToken dan order_id sebagai respons JSON
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $order->id,
                'invoice_number' => $order->invoice_number,
                'first_name' => $first_name,
            ]);
        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            DB::rollBack();
            return redirect()->route('cart')->with('error', $e->getMessage());
        }
    }
    private function getApplicableDiscounts($productId, $categoryId)
    {
        $now = now();

        return DB::table('discounts')
            ->where(function ($query) use ($productId, $categoryId) {
                $query->where('applicable_to', 'Semua') // Diskon untuk semua produk
                    ->orWhere(function ($query) use ($productId) {
                        $query->where('applicable_to', 'Product')
                            ->whereJsonContains('applicable_ids', (string) $productId); // Pastikan productId dikonversi ke string
                    })
                    ->orWhere(function ($query) use ($categoryId) {
                        $query->where('applicable_to', 'Category')
                            ->whereJsonContains('applicable_ids', (string) $categoryId); // Pastikan categoryId dikonversi ke string
                    });
            })
            ->where('start_date', '<=', $now) // Diskon aktif
            ->where('end_date', '>=', $now) // Diskon belum kadaluarsa
            ->get();
    }

    private function calculateDiscountedPrice($price, $discounts)
    {
        $discountedPrice = $price;

        foreach ($discounts as $discount) {
            $discountedPrice -= $discountedPrice * ($discount->discount_percentage / 100);
        }

        // Pastikan harga diskon tidak kurang dari 0
        return max($discountedPrice, 0);
    }

    private function generateInvoiceNumber()
    {
        // Contoh format: INV-YYYYMMDD-XXXX
        $date = now()->format('Ymd');
        $lastOrder = Order::where('invoice_number', 'like', 'INV-' . $date . '-%')->orderBy('invoice_number', 'desc')->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->invoice_number, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return 'INV-' . $date . '-' . $nextNumber;
    }
}