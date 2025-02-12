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
            })
            ->get();

        return view('welcome', compact(
            'newArrivals',
            'topProductsAll',
            'topProductsWallet',
            'topProductsBag',
            'mostReviewedProducts',
        ));
    }
}
