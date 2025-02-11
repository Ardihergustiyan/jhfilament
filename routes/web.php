<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Routes for Login and Logout
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
// Route::post('/register', [RegisterController::class, 'store']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home and Redirect Routes
Route::get('/', function () {
    return redirect('/home');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Product Routes
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product-detail/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search-products', [ProductController::class, 'search']);

// Cart Routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::patch('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart.remove');
    Route::get('/cart/item-count', [CartController::class, 'getCartItemCount'])->name('cart.itemCount');
    Route::get('/cart/subtotal', [CartController::class, 'getSubtotal'])->name('cart.subtotal');
    Route::post('/cart/proceed-to-checkout', [CartController::class, 'proceedToCheckout'])->name('cart.proceed-to-checkout');
    Route::post('/cart/update-session', [CartController::class, 'updateSession'])->name('cart.update-session');
    Route::post('/validate-voucher', [CartController::class, 'validateVoucher'])->name('validateVoucher');
});

// Route::get('/cart', [CartController::class, 'index'])->middleware('auth')->name('cart.index');
// Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
// Route::patch('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
// Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart.remove');
// Route::get('/cart/item-count', [CartController::class, 'getCartItemCount'])->name('cart.itemCount');
// Route::get('/cart/subtotal', [CartController::class, 'getSubtotal']);
// Route::post('/proceed-to-checkout', [CartController::class, 'proceedToCheckout'])->name('proceedToCheckout');
// Route::post('/validate-voucher', [CartController::class, 'validateVoucher']);

// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
});

// Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
// Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/{first_name}', [ProfileController::class, 'show'])->name('profile');
    Route::put('/user/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/join-reseller', [ProfileController::class, 'join'])->name('profile.join');
    Route::post('/upgrade-reseller', [ProfileController::class, 'upgradeReseller'])->name('profile.upgradeReseller');
    Route::get('/progress-bar-data', [ProfileController::class, 'getProgressData'])->name('progress.data');
    Route::put('/orders/{order}/cancel', [ProfileController::class, 'cancel'])->name('orders.cancel');
    Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');
});

// Static Pages
Route::get('/about', function () {
    return view('about');
});

Route::get('/thank-you/{order_id}', [OrderController::class, 'thankyou'])
     ->name('order.thankyou');
Route::get('/payment/success', [OrderController::class, 'success'])->name('payment.success');

// Route::get('/profile/{first_name}', [ProfileController::class, 'show'])
//     ->middleware('auth')
//     ->name('profile');
// Route::put('/user/update', [ProfileController::class, 'update'])->name('profile.update');
// Route::post('/join-reseller', [ProfileController::class, 'join'])->middleware('auth');
// Route::post('/upgrade-reseller', [ProfileController::class, 'upgradeReseller'])->middleware('auth');
// Route::get('/progress-bar-data', [ProfileController::class, 'getProgressData'])->name('progress.data');
// Route::put('/orders/{order}/cancel', [ProfileController::class, 'cancel'])->name('orders.cancel');
// Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');


