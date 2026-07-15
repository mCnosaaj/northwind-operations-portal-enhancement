<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/reports/inventory')->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/data', [CustomerController::class, 'data'])->name('customers.data');
Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/customer/{customer}', [OrderController::class, 'customerOrders'])->name('orders.customer');

Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('/reports/sales/data', [ReportController::class, 'salesData'])->name('reports.sales.data');
Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
