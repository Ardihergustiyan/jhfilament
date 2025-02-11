<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    
    public function index()
    {
        // Produk terbaru
        $newArrivals = Product::latest()->take(5)->get();

        // Produk terlaris untuk kategori All
        $topProductsAll = Product::topSelling()->get();

        // Produk terlaris untuk kategori Wallet
        $topProductsWallet = Product::topSelling('wallet')->take(10)->get();

        // Produk terlaris untuk kategori Bag
        $topProductsBag = Product::topSelling('bag')->take(10)->get();

        // Produk dengan jumlah ulasan terbanyak
        $mostReviewedProducts = Product::mostReviewed()->get();

        // @dd($topProductsAll, $topProductsWallet, $topProductsBag);

        return view('welcome', compact(
            'newArrivals',
            'topProductsAll',
            'topProductsWallet',
            'topProductsBag',
            'mostReviewedProducts',
        ));
    }

    
}
