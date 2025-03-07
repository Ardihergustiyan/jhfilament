<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

// // admin
// Route::get('admin', function () {
//     return response()->view('errors.404', [], 404);
// })->middleware(['auth', 'verified', 'role:Admin']);

// Route::prefix('admin')->group(function () {
//     Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('/login', [AdminAuthController::class, 'login']);
//     Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
// });


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
    Route::patch('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart.remove');
    Route::get('/cart/item-count', [CartController::class, 'getCartItemCount'])->name('cart.itemCount');
    Route::get('/cart/subtotal', [CartController::class, 'getSubtotal'])->name('cart.subtotal');
    Route::post('/cart/proceed-to-checkout', [CartController::class, 'proceedToCheckout'])->name('cart.proceed-to-checkout');
    Route::post('/cart/update-session', [CartController::class, 'updateSession'])->name('cart.update-session');
    Route::post('/validate-voucher', [CartController::class, 'validateVoucher'])->name('validateVoucher');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
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

Route::middleware('auth')->group(function () {
    Route::get('/profile/{first_name}/order/{invoice_number}', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::get('/payment/success', [OrderController::class, 'success'])->name('payment.success');
});
// Route::get('/profile/{first_name}', [ProfileController::class, 'show'])
//     ->middleware('auth')
//     ->name('profile');
// Route::put('/user/update', [ProfileController::class, 'update'])->name('profile.update');
// Route::post('/join-reseller', [ProfileController::class, 'join'])->middleware('auth');
// Route::post('/upgrade-reseller', [ProfileController::class, 'upgradeReseller'])->middleware('auth');
// Route::get('/progress-bar-data', [ProfileController::class, 'getProgressData'])->name('progress.data');
// Route::put('/orders/{order}/cancel', [ProfileController::class, 'cancel'])->name('orders.cancel');
// Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');


Route::post('/midtrans-callback', [MidtransController::class, 'handleMidtransCallback']);
Route::post('/midtrans/notification', [MidtransController::class,'handleMidtransNotification']);

Route::get('auth/redirect', [SocialiteController::class, 'redirect']);

Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);

// Route::get('/home', [HomeController::class, 'index'])
//     ->middleware(['auth', 'verified']);


// Tampilan notifikasi verifikasi email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handle verifikasi email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Kirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/order/{order}/reorder', [ProfileController::class, 'reorder'])->name('order.reorder');