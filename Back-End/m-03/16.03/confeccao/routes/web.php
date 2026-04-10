<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// Clients
Route::resource('clients', ClientController::class);
// Route::get('/clients', [ClientController::class, 'index']) -> name('clients.index');
// Route::get('/clients/create', [ClientController::class, 'create']) -> name('clients.create');
// Route::get('/clients/edit', [ClientController::class, 'edit']) -> name('clients.edit');
// Route::get('/clients/update', [ClientController::class, 'update']) -> name('clients.update');
// Route::get('/clients/destroy', [ClientController::class, 'destroy']) -> name('clients.destroy');
// Route::post('/clients', [ClientController::class, 'store']) -> name('clients.store');

// Orders
Route::resource('orders', OrderController::class);
// Route::get('/orders', [OrderController::class, 'index']) -> name('orders.index');
// Route::get('/orders/create', [OrderController::class, 'create']) -> name('orders.create');
// Route::get('/orders/edit', [OrderController::class, 'edit']) -> name('orders.edit');
// Route::get('/orders/update', [OrderController::class, 'update']) -> name('orders.update');
// Route::get('/orders/destroy', [OrderController::class, 'destroy']) -> name('orders.destroy');
// Route::post('/orders', [OrderController::class, 'store']) -> name('orders.store');

// Suppliers
Route::resource('suppliers', SupplierController::class);
// Route::get('/suppliers', [SupplierController::class, 'index']) -> name('suppliers.index');
// Route::get('/suppliers/create', [SupplierController::class, 'create']) -> name('suppliers.create');
// Route::get('/suppliers/edit', [SupplierController::class, 'edit']) -> name('suppliers.edit');
// Route::get('/suppliers/update', [SupplierController::class, 'update']) -> name('suppliers.update');
// Route::get('/suppliers/destroy', [SupplierController::class, 'destroy']) -> name('suppliers.destroy');
// Route::post('/suppliers', [SupplierController::class, 'store']) -> name('suppliers.store');

// Stocks
Route::resource('stocks', StockController::class);
// Route::get('/stocks', [StockController::class, 'index']) -> name('stocks.index');
// Route::get('/stocks/create', [StockController::class, 'create']) -> name('stocks.create');
// Route::get('/stocks/edit', [StockController::class, 'edit']) -> name('stocks.edit');
// Route::get('/stocks/update', [StockController::class, 'update']) -> name('stocks.update');
// Route::get('/stocks/destroy', [StockController::class, 'destroy']) -> name('stocks.destroy');
// Route::post('/stocks', [StockController::class, 'store']) -> name('stocks.store');

// Products
Route::resource('products', ProductController::class);
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::get('/products/create', [ProductController::class, 'create']) -> name('products.create');
// Route::get('/products/edit', [ProductController::class, 'edit'])->name('products.edit');
// Route::get('/products/edit', [ProductController::class, 'edit'])->name('products.edit');
// Route::get('/products/edit', [ProductController::class, 'edit'])->name('products.edit');
// Route::post('/products', [ProductController::class, 'store']) -> name('products.store');


// ------
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
