<?php

use App\Livewire\Cart;
use App\Livewire\ProductList;
use App\Livewire\ProductShow;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;

Route::view('/', 'welcome');

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

require __DIR__.'/auth.php';
