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
Route::get('/clients', [ClientController::class, 'index']) -> name('clients.index');
Route::get('/clients/create', [ClientController::class, 'create']) -> name('clients.create');
Route::post('/clients', [ClientController::class, 'store']) -> name('clients.store');



// Orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderController::class, 'create']) -> name('orders.create');
Route::post('/orders', [OrderController::class, 'store']) -> name('orders.store');



// Suppliers
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/create', [SupplierController::class, 'create']) -> name('suppliers.create');


Route::post('/suppliers', [SupplierController::class, 'store']) -> name('suppliers.store');

// Stocks
Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('/stocks/create', [StockController::class, 'create']) -> name('stocks.create');
Route::post('/stocks', [StockController::class, 'store']) -> name('stocks.store');



// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create']) -> name('products.create');
Route::post('/products', [ProductController::class, 'store']) -> name('products.store');



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
