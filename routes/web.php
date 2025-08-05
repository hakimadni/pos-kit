<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuSetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::resource('orders', OrderController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('menuSets', MenuSetController::class)->except(['show']);
    Route::resource('carts', CartController::class)->except(['show', 'edit', 'index', 'create']);
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
