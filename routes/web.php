<?php

use App\Livewire\Cart;
use App\Livewire\ProductList;
use App\Livewire\ProductShow;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;

// Home page with featured products and deals
Route::get('/', [CustomerController::class, 'index'])->name('home');

// Product routes (Livewire)
Route::get('/products', ProductList::class)->name('products.index');
Route::get('/products/{slug}', ProductShow::class)->name('products.show');

Route::get('/search/products', [CustomerController::class, 'searchProducts'])
    ->name('search.products')
    ->middleware('throttle:60,1');

// Cart routes (Livewire)
Route::middleware('auth')->group(function () {
    Route::get('/cart', Cart::class)->name('cart');
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
});

// Order routes
Route::middleware(['auth'])->group(function () {
    Route::post('/order/place', [CustomerController::class, 'placeOrder'])->name('order.place');
    Route::get('/my-orders', [CustomerController::class, 'orders'])->name('user.orders');
    Route::get('/order/track/{id}', [CustomerController::class, 'trackOrder'])->name('order.track');
    Route::put('/order/cancel/{id}', [CustomerController::class, 'cancelOrder'])->name('order.cancel');
});

// User profile routes (authenticated)
Route::middleware(['auth'])->group(function () {
    // Dashboard redirects to user profile (for post-login redirect compatibility)
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    // Profile editing page
    Route::view('/profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
