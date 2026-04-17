<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/store/{slug}', [\App\Http\Controllers\ShopController::class, 'storeView'])->name('store');
Route::get('/marketplace', [\App\Http\Controllers\ShopController::class, 'marketplace'])->name('marketplace');
Route::get('/brands', [\App\Http\Controllers\ShopController::class, 'brandsPage'])->name('brands');
Route::get('/categories', [\App\Http\Controllers\ShopController::class, 'categoriesPage'])->name('categories');
Route::get('/product/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::middleware(['auth:web,buyer'])->group(function () {
    // Buyer and web user routes
    Route::get('dashboard', [\App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');
    Route::get('purchases', [\App\Http\Controllers\UserController::class, 'purchases'])->name('purchases');
    Route::get('wishlist', [\App\Http\Controllers\UserController::class, 'wishlist'])->name('wishlist');
});

Route::middleware(['auth:brand'])->group(function () {
    // Brand only routes
    Route::get('brand/dashboard', [\App\Http\Controllers\BrandController::class, 'dashboard'])->name('brand.dashboard');
    Route::get('brand/onboarding', [\App\Http\Controllers\BrandController::class, 'onboarding'])->name('brand.onboarding');
    Route::post('brand/onboarding', [\App\Http\Controllers\BrandController::class, 'storeOnboarding']);
    Route::get('brand/help', [\App\Http\Controllers\BrandController::class, 'help'])->name('brand.help');

    // Products
    Route::get('brand/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('brand.products');
    Route::get('brand/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('brand.products.create');
    Route::get('brand/products/add', [\App\Http\Controllers\ProductController::class, 'create']); // Alias for compatibility
    Route::post('brand/products/store', [\App\Http\Controllers\ProductController::class, 'store'])->name('brand.products.store');
    Route::get('brand/products/edit/{id}', [\App\Http\Controllers\ProductController::class, 'edit'])->name('brand.products.edit');
    Route::post('brand/products/update/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('brand.products.update');
    Route::post('brand/products/action', [\App\Http\Controllers\ProductController::class, 'handleAction'])->name('brand.products.action');

    // Orders
    Route::get('brand/orders', [\App\Http\Controllers\OrderController::class, 'brandOrders'])->name('brand.orders');
    Route::get('brand/orders/{id}', [\App\Http\Controllers\OrderController::class, 'brandOrderDetails'])->name('brand.orders.show');
    Route::post('brand/orders/{id}/status', [\App\Http\Controllers\OrderController::class, 'updateOrderStatus'])->name('brand.orders.status');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
