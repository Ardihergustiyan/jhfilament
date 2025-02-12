<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter rentang harga dari request
        $minPrice = $request->query('min_price', 10000); // Default minimum price
        $maxPrice = $request->query('max_price', 1000000); // Default maximum price
        
        $resellerLevelId = auth()->user()->reseller_level_id ?? null;
        // Query Produk
        $query = Product::select(
            'products.*',
            'aggregated_data.total_quantity',
            'images.main_image',
            'products.image as product_image',
            'ratings.average_rating',
            'review_counts.total_reviews',
            DB::raw('IFNULL(product_prices.price, products.het_price) as final_price') // Gunakan harga reseller atau het_price
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
        
        // Filter rentang harga
        // $query->whereBetween('products.het_price', [$minPrice, $maxPrice]);
        $query->whereBetween(
            DB::raw('IFNULL(product_prices.price, products.het_price)'), 
            [$minPrice, $maxPrice]
        );
    
        // Filter kategori dan lainnya (tetap sama)
        $category = $request->query('category', 'all');
        $selectedCategories = $request->query('categories') 
            ? explode(',', $request->query('categories')) 
            : [];
        
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

        if ($request->has('query') && $request->query('query') !== '') {
            $query->where('products.name', 'LIKE', "%{$request->query('query')}%");
        }
        
    
        // Filter berdasarkan sorting
        $filter = $request->query('filter');
        if ($filter === 'trending') {
            $query->orderBy('review_counts.total_reviews', 'desc');
        } elseif ($filter === 'terbaru') {
            $query->orderBy('products.created_at', 'desc');
        } elseif ($filter === 'terlaris') {
            $query->orderBy('aggregated_data.total_quantity', 'desc');
        } elseif ($filter === 'termahal') {
            $query->orderBy('final_price', 'desc');
        } elseif ($filter === 'termurah') {
            $query->orderBy('final_price', 'asc');
        }
        
    
        // Pagination
        $allProducts = $query->paginate(20, ['*'], 'page', $request->query('page', 1));
    
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
                'allProducts' => $allProducts,
            ]);
        }

        return view('product', [
            'allProducts' => $allProducts,
            'productTypes' => $productTypes,
            'selectedCategoryNames' => $selectedCategories,
            'hasMorePages' => $allProducts->hasMorePages(),
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
            DB::raw('IFNULL(product_prices.price, products.het_price) as final_price') // Tambahkan kolom final_price
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
}
