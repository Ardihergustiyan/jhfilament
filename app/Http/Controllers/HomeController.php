<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil level reseller user (jika ada)
        $resellerLevelId = auth()->check() && auth()->user()->hasRole('Reseller') 
            ? auth()->user()->reseller_level_id 
            : null;
    
        // Produk terbaru
        $newArrivals = Product::select('products.*')
            ->when($resellerLevelId, function ($query) use ($resellerLevelId) {
                $query->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                    $join->on('products.id', '=', 'product_prices.product_id')
                        ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                })
                ->addSelect(DB::raw('IFNULL(product_prices.price, products.het_price) as final_price'));
            }, function ($query) {
                $query->addSelect(DB::raw('products.het_price as final_price'));
            })
            ->latest()
            ->take(5)
            ->get();
    
        // Produk terlaris untuk kategori All
        $topProductsAll = Product::topSelling()
            ->when($resellerLevelId, function ($query) use ($resellerLevelId) {
                $query->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                    $join->on('products.id', '=', 'product_prices.product_id')
                        ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                })
                ->addSelect(DB::raw('IFNULL(product_prices.price, products.het_price) as final_price'));
            }, function ($query) {
                $query->addSelect(DB::raw('products.het_price as final_price'));
            })
            ->get();
    
        // Produk terlaris untuk kategori Wallet
        $topProductsWallet = Product::topSelling('wallet')
            ->when($resellerLevelId, function ($query) use ($resellerLevelId) {
                $query->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                    $join->on('products.id', '=', 'product_prices.product_id')
                        ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                })
                ->addSelect(DB::raw('IFNULL(product_prices.price, products.het_price) as final_price'));
            }, function ($query) {
                $query->addSelect(DB::raw('products.het_price as final_price'));
            })
            ->take(10)
            ->get();
    
        // Produk terlaris untuk kategori Bag
        $topProductsBag = Product::topSelling('bag')
            ->when($resellerLevelId, function ($query) use ($resellerLevelId) {
                $query->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                    $join->on('products.id', '=', 'product_prices.product_id')
                        ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                })
                ->addSelect(DB::raw('IFNULL(product_prices.price, products.het_price) as final_price'));
            }, function ($query) {
                $query->addSelect(DB::raw('products.het_price as final_price'));
            })
            ->take(10)
            ->get();
    
        // Produk dengan jumlah ulasan terbanyak
        $mostReviewedProducts = Product::mostReviewed()
            ->when($resellerLevelId, function ($query) use ($resellerLevelId) {
                $query->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                    $join->on('products.id', '=', 'product_prices.product_id')
                        ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
                })
                ->addSelect(DB::raw('IFNULL(product_prices.price, products.het_price) as final_price'));
            }, function ($query) {
                $query->addSelect(DB::raw('products.het_price as final_price'));
            })
            ->get();
    
        // Gabungkan semua produk ke dalam satu koleksi
        $allProducts = collect([
            ...$newArrivals,
            ...$topProductsAll,
            ...$topProductsWallet,
            ...$topProductsBag,
            ...$mostReviewedProducts,
        ]);
    
        // Hitung diskon untuk setiap produk
        foreach ($allProducts as $product) {
            $discounts = $this->getApplicableDiscounts($product->id, $product->category_id);
            $basePrice = $product->final_price ?? $product->het_price; // Gunakan final_price jika ada, jika tidak gunakan het_price
            $product->discounted_price = $this->calculateDiscountedPrice($basePrice, $discounts);
            $product->total_discount = $this->calculateTotalDiscount($discounts); // Simpan total diskon
            $product->discount_display = $this->formatDiscountDisplay($discounts); // Simpan format tampilan diskon
        }
    
        // Filter produk yang memiliki diskon (total_discount > 0)
        $discountedProducts = $allProducts->filter(function ($product) {
            return $product->total_discount > 0;
        })->take(2); // Ambil 2 produk untuk ditampilkan di banner
    
        return view('welcome', compact(
            'newArrivals',
            'topProductsAll',
            'topProductsWallet',
            'topProductsBag',
            'mostReviewedProducts',
            'discountedProducts',
        ));
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

    private function calculateTotalDiscount($discounts)
    {
        $totalDiscount = 0;

        foreach ($discounts as $discount) {
            $totalDiscount += $discount->discount_percentage;
        }

        return $totalDiscount;
    }

    private function formatDiscountDisplay($discounts)
    {
        $discountTexts = [];

        foreach ($discounts as $discount) {
            $discountTexts[] = $discount->discount_percentage . '%';
        }

        return implode(' + ', $discountTexts);
    }
}
