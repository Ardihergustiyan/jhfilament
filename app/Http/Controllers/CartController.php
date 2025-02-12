<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = auth()->user();
        $resellerLevelId = $user->reseller_level_id ?? null;

        // Mengambil item keranjang beserta produk, gambar utama, dan gambar produk
        $cartItems = Cart::select(
                'carts.*', 
                'products.name as product_name', 
                'products.image as product_image', 
                'variants.main_image',
                DB::raw('IFNULL(product_prices.price, products.het_price) as final_price') // Gunakan harga reseller atau het_price
            )
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
            })
            ->leftJoinSub(
                DB::table('product_variants')
                    ->select('id as variant_id', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(image, "$[0]")) as main_image'))
                    ->groupBy('id'),
                'variants',
                'carts.variant_id',
                '=',
                'variants.variant_id'
            )
            ->where('carts.user_id', $user->id)
            ->get();

        // Ambil variant yang sesuai dengan produk di keranjang
        $variants = ProductVariant::whereIn('product_id', $cartItems->pluck('product_id'))
            ->select('id', 'product_id', 'color', 'image') // Include variant image
            ->get();

        // Kelompokkan variants berdasarkan product_id
        $variantsGrouped = $variants->groupBy('product_id');

        // Perhitungan subtotal
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

        // Check jika keranjang kosong
        $isCartEmpty = $cartItems->isEmpty();

        // Return view dengan semua variabel yang dibutuhkan
        return response()
            ->view('cart', compact(
                'cartItems',
                'isCartEmpty',
                'variantsGrouped',
                'subtotal',
                'voucherAmount',
                'total'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function addToCart(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Ambil harga reseller jika ada
        $resellerLevelId = auth()->user()->reseller_level_id ?? null;
        $finalPrice = $product->het_price;

        if ($resellerLevelId) {
            $productPrice = ProductPrice::where('product_id', $product->id)
                ->where('reseller_level_id', $resellerLevelId)
                ->first();

            if ($productPrice) {
                $finalPrice = $productPrice->price;
            }
        }

        // Cek stok
        if ($request->variant_id) {
            // Jika ada varian, cek stok varian
            $variant = ProductVariant::findOrFail($request->variant_id);
            if ($variant->stock < $request->quantity) {
                return response()->json([
                    'message' => 'Stok varian tidak mencukupi!',
                ], 400);
            }
        } else {
            // Jika tidak ada varian, cek stok produk
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'message' => 'Stok produk tidak mencukupi!',
                ], 400);
            }
        }

        // Periksa apakah produk sudah ada di keranjang pengguna
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->when($request->variant_id, function ($query, $variantId) {
                return $query->where('variant_id', $variantId);
            })
            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan jumlahnya
            $newQuantity = $cartItem->quantity + $request->quantity;

            // Cek stok lagi untuk memastikan stok mencukupi
            if ($request->variant_id) {
                if ($variant->stock < $newQuantity) {
                    return response()->json([
                        'message' => 'Stok varian tidak mencukupi untuk jumlah yang diminta!',
                    ], 400);
                }
            } else {
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'message' => 'Stok produk tidak mencukupi untuk jumlah yang diminta!',
                    ], 400);
                }
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Jika belum ada, tambahkan produk ke keranjang
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'variant_id' => $request->variant_id,
                'final_price' => $finalPrice, // Simpan harga final
            ]);
        }

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
        ], 200);
    }

    public function getCartData()
    {
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
            return ($item->final_price ?? 0) * ($item->quantity ?? 0);
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

        return response()->json([
            'subtotal' => $subtotal,
            'voucherAmount' => $voucherAmount,
            'total' => $total,
        ]);
    }

    public function update(Request $request, $cartItemId)
    {
        // Validasi request
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        // Ambil item cart
        $cartItem = Cart::where('id', $cartItemId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Jika quantity diubah, cek stok
        if ($request->has('quantity')) {
            $newQuantity = $request->quantity;

            // Cek stok produk atau varian
            if ($cartItem->variant_id) {
                // Jika ada varian, cek stok varian
                $variant = ProductVariant::findOrFail($cartItem->variant_id);
                if ($variant->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok varian tidak mencukupi!',
                    ], 400);
                }
            } else {
                // Jika tidak ada varian, cek stok produk
                $product = Product::findOrFail($cartItem->product_id);
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok produk tidak mencukupi!',
                    ], 400);
                }
            }

            // Update quantity
            $cartItem->quantity = $newQuantity;
        }

        // Update variant_id jika diubah
        if ($request->has('variant_id')) {
            $cartItem->variant_id = $request->variant_id;
        }

        $cartItem->save();

        // Hitung ulang subtotal
        $cartItems = Cart::where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->final_price ?? 0) * ($item->quantity ?? 0);
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

        $total = $subtotal - $voucherAmount;

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'voucherAmount' => $voucherAmount,
            'total' => $total,
        ]);
    }
  
    public function delete($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus dari keranjang!',
        ], 200);
    }

    public function getCartItemCount()
    {
        $cartItemCount = Cart::where('user_id', auth()->id())->count();

        return response()->json([
            'count' => $cartItemCount,
        ], 200);
    }

    public function proceedToCheckout(Request $request)
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

        // Jika keranjang kosong, kembalikan ke halaman cart dengan pesan error
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return ($item->final_price ?? 0) * ($item->quantity ?? 0);
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
                // Hitung voucherAmount berdasarkan persentase diskon
                $voucherAmount = ($subtotal * $voucher->discount_percentage) / 100;
            } else {
                // Jika voucher tidak valid, hapus voucher_id dari keranjang
                Cart::where('user_id', $user->id)->update(['voucher_id' => null]);
            }
        }

        // Hitung total
        $total = $subtotal - $voucherAmount;

        // Redirect ke halaman checkout dengan data cart
        return redirect()->route('checkout')->with([
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'voucherAmount' => $voucherAmount,
            'total' => $total,
        ]);
    }

    public function validateVoucher(Request $request)
    {
        $voucherCode = $request->input('code');
        $user = auth()->user();

        // Cari voucher berdasarkan kode
        $voucher = Voucher::where('code', $voucherCode)
                        ->where('status', 'Unused') // Pastikan status voucher masih Unused
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

        if ($voucher) {
            // Periksa apakah voucher berlaku untuk semua pengguna atau pengguna tertentu
            if ($voucher->user_id === null || $voucher->user_id === $user->id) {
                // Periksa apakah user sudah pernah menggunakan voucher ini sebelumnya
                $hasUsedVoucher = Order::where('user_id', $user->id)
                                    ->where('voucher_id', $voucher->id)
                                    ->exists();

                if ($hasUsedVoucher) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'Anda sudah menggunakan voucher ini sebelumnya.'
                    ]);
                }

                // Simpan voucher_id ke keranjang user
                Cart::where('user_id', $user->id)->update(['voucher_id' => $voucher->id]);

                return response()->json([
                    'valid' => true,
                    'voucher' => $voucher->discount_percentage
                ]);
            } else {
                return response()->json([
                    'valid' => false,
                    'message' => 'Voucher tidak berlaku untuk Anda'
                ]);
            }
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher tidak valid atau sudah digunakan'
            ]);
        }
    }

}
