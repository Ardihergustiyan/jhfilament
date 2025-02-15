<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter rentang harga dari request
        $minPrice = $request->query('min_price', 10000); // Default minimum price
        $maxPrice = $request->query('max_price', 1000000); // Default maximum price
        
        // Ambil kategori dari request
        $category = $request->query('category', 'all'); // Definisikan variabel $category di sini
        $selectedCategories = $request->query('categories') 
            ? explode(',', $request->query('categories')) 
            : [];
        
        $resellerLevelId = auth()->user()->reseller_level_id ?? null;
        
        // Query Produk
        $query = Product::select(
            'products.*',
            'aggregated_data.total_quantity',
            'images.main_image',
            'products.image as product_image',
            'ratings.average_rating',
            'review_counts.total_reviews',
            DB::raw('IFNULL(product_prices.price, products.het_price) as final_price'), // Gunakan harga reseller atau het_price
            DB::raw('IFNULL(product_prices.price, products.het_price) as discount_price') // Tambahkan base_price untuk perhitungan diskon
        )
        ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
            $join->on('products.id', '=', 'product_prices.product_id')
                ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
        })
        ->leftJoinSub(
            DB::table('order_items')
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id'),
            'aggregated_data',
            'products.id',
            '=',
            'aggregated_data.product_id'
        )
        ->leftJoinSub(
            DB::table('product_variants')
                ->select('product_id', DB::raw('MAX(JSON_UNQUOTE(JSON_EXTRACT(image, "$[0]"))) as main_image'))
                ->groupBy('product_id'),
            'images',
            'products.id',
            '=',
            'images.product_id'
        )
        ->leftJoinSub(
            DB::table('product_reviews')
                ->select('product_id', DB::raw('AVG(rating) as average_rating'))
                ->groupBy('product_id'),
            'ratings',
            'products.id',
            '=',
            'ratings.product_id'
        )
        ->leftJoinSub(
            DB::table('product_reviews')
                ->select('product_id', DB::raw('COUNT(id) as total_reviews'))
                ->groupBy('product_id'),
            'review_counts',
            'products.id',
            '=',
            'review_counts.product_id'
        )
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->join('general_categories', 'categories.general_category_id', '=', 'general_categories.id');
        
        // Filter berdasarkan kategori
        if ($category && $category !== 'all') {
            $query->where('general_categories.slug', $category);

            if (!empty($selectedCategories)) {
                $query->where(function ($q) use ($selectedCategories) {
                    foreach ($selectedCategories as $slug) {
                        $q->orWhere('categories.slug', 'LIKE', "%{$slug}%");
                    }
                });
            }
        }

        if ($category === 'all' && !empty($selectedCategories)) {
            $query->where(function ($q) use ($selectedCategories) {
                foreach ($selectedCategories as $slug) {
                    $q->orWhere('categories.slug', 'LIKE', "%{$slug}%");
                }
            });
        }

        // Filter berdasarkan query pencarian
        if ($request->has('query') && $request->query('query') !== '') {
            $query->where('products.name', 'LIKE', "%{$request->query('query')}%");
        }

        // Hitung harga setelah diskon untuk setiap produk
        $allProducts = $query->get();

        foreach ($allProducts as $product) {
            $discounts = $this->getApplicableDiscounts($product->id, $product->category_id);
            $product->discounted_price = $this->calculateDiscountedPrice($product->final_price, $discounts);
            $product->total_discount = $this->calculateTotalDiscount($discounts); // Simpan total diskon
            $product->discount_display = $this->formatDiscountDisplay($discounts); // Simpan format tampilan diskon
        }

        // Filter berdasarkan rentang harga setelah diskon
        $allProducts = $allProducts->filter(function ($product) use ($minPrice, $maxPrice) {
            return $product->discounted_price >= $minPrice && $product->discounted_price <= $maxPrice;
        });

        // Filter berdasarkan sorting
        $filter = $request->query('filter');
        if ($filter === 'trending') {
            $allProducts = $allProducts->sortByDesc('total_reviews');
        } elseif ($filter === 'terbaru') {
            $allProducts = $allProducts->sortByDesc('created_at');
        } elseif ($filter === 'terlaris') {
            $allProducts = $allProducts->sortByDesc('total_quantity');
        } elseif ($filter === 'termahal') {
            $allProducts = $allProducts->sortByDesc('discounted_price');
        } elseif ($filter === 'termurah') {
            $allProducts = $allProducts->sortBy('discounted_price');
        } 

        // Pagination
        $page = $request->query('page', 1);
        $perPage = 20;
        $paginatedProducts = new LengthAwarePaginator(
            $allProducts->forPage($page, $perPage),
            $allProducts->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ambil tipe produk untuk filter checkbox
        $productTypes = DB::table('categories')
        ->select(
            'categories.id as category_id',
            'categories.name as category_name',
            DB::raw("REPLACE(categories.slug, CONCAT(SUBSTRING_INDEX(categories.slug, '-', 1), '-'), '') as category_slug")
        )
        ->when($category !== 'all', function ($query) use ($category) {
            $query->join('general_categories', 'categories.general_category_id', '=', 'general_categories.id')
                ->where('general_categories.slug', $category);
        })
        ->groupBy('categories.id', 'categories.name', 'category_slug') // Masukkan semua kolom SELECT ke dalam GROUP BY
        ->distinct()
        ->get()
        ->unique('category_name'); // Hapus duplikasi berdasarkan nama kategori

        // Pastikan selectedCategories tidak mengandung duplikasi
        $selectedCategories = array_unique($selectedCategories);

        if ($request->ajax()) {
            return view('partials.products', [
                'allProducts' => $paginatedProducts,
            ]);
        }

        return view('product', [
            'allProducts' => $paginatedProducts,
            'productTypes' => $productTypes,
            'selectedCategoryNames' => $selectedCategories,
        ]);
    }

    public function show($slug, Request $request)
    {
        $starFilter = $request->query('stars', null);

        // Ambil level reseller user (jika ada)
        $resellerLevelId = auth()->check() && auth()->user()->hasRole('Reseller') 
            ? auth()->user()->reseller_level_id 
            : null;

        // Ambil produk berdasarkan slug
        $product = Product::select(
            'products.*',
            'ratings.average_rating',
            'review_counts.total_reviews',
            DB::raw('IFNULL(product_prices.price, products.het_price) as final_price'), // Gunakan harga reseller atau het_price
            DB::raw('IFNULL(product_prices.price, products.het_price) as discount_price') // Tambahkan base_price untuk perhitungan diskon
        )
        ->leftJoinSub(
            DB::table('product_reviews')
                ->select('product_id', DB::raw('AVG(rating) as average_rating'))
                ->groupBy('product_id'),
            'ratings',
            'products.id',
            '=',
            'ratings.product_id'
        )
        ->leftJoinSub(
            DB::table('product_reviews')
                ->select('product_id', DB::raw('COUNT(id) as total_reviews'))
                ->groupBy('product_id'),
            'review_counts',
            'products.id',
            '=',
            'review_counts.product_id'
        )
        ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
            $join->on('products.id', '=', 'product_prices.product_id')
                ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
        })
        ->where('products.slug', $slug)
        ->firstOrFail();

        // Hitung diskon untuk produk ini
        $discounts = $this->getApplicableDiscounts($product->id, $product->category_id);
        $product->discounted_price = $this->calculateDiscountedPrice($product->final_price, $discounts);
        $product->total_discount = $this->calculateTotalDiscount($discounts); // Simpan total diskon
        $product->discount_display = $this->formatDiscountDisplay($discounts); // Simpan format tampilan diskon

        // Ambil gambar utama (gambar pertama dari kolom image)
        $productImages = is_array($product->image) ? $product->image : json_decode($product->image, true); // Pastikan data berupa array
        $mainImage = $productImages[0] ?? null; // Gunakan gambar pertama sebagai gambar utama

        // Ambil varian produk beserta gambar
        $variants = DB::table('product_variants')
            ->select('id', 'color', 'image', 'stock')
            ->where('product_id', $product->id)
            ->get()
            ->map(function ($variant) {
                $variant->images = json_decode($variant->image, true); // Decode JSON gambar varian
                return $variant;
            });

        // Ambil ulasan terkait produk
        $reviewsQuery = DB::table('product_reviews')
            ->join('users', 'product_reviews.user_id', '=', 'users.id')
            ->select(
                'product_reviews.*',
                'users.first_name',
                'users.last_name',
                'users.image as profile_image'
            )
            ->where('product_reviews.product_id', $product->id);

        if ($starFilter) {
            $reviewsQuery->where('product_reviews.rating', $starFilter);
        }

        $reviews = $reviewsQuery->get();

        return view('product-detail', [
            'product' => $product,
            'mainImage' => $mainImage,         // Gambar utama
            'productImages' => $productImages, // Semua gambar
            'variants' => $variants,          // Variasi produk
            'reviews' => $reviews,
        ]);
    }


    public function search(Request $request)
    {
        $searchQuery = $request->input('query', '');

        if (strlen($searchQuery) === 0) {
            return response()->json([
                'status' => 'success',
                'data' => [],
            ]);
        }

        // Ambil level reseller user (jika ada)
        $resellerLevelId = auth()->check() && auth()->user()->hasRole('Reseller') 
            ? auth()->user()->reseller_level_id 
            : null;

        // Ambil 5 produk terlaris yang sesuai dengan pencarian
        $products = Product::where('name', 'LIKE', "%{$searchQuery}%")
            ->leftJoinSub(
                DB::table('order_items')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->groupBy('product_id'),
                'aggregated_data',
                'products.id',
                '=',
                'aggregated_data.product_id'
            )
            ->leftJoin('product_prices', function ($join) use ($resellerLevelId) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.reseller_level_id', '=', $resellerLevelId);
            })
            ->orderBy('aggregated_data.total_quantity', 'desc') // Urutkan berdasarkan penjualan terbanyak
            ->select(
                'products.id', 
                'products.name', 
                'products.slug', 
                'products.base_price', 
                'products.het_price',
                DB::raw('IFNULL(product_prices.price, products.het_price) as final_price') // Tambahkan kolom final_price
            )
            ->take(3) // Batasi hasil maksimal 5
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
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
