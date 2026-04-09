<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

// Admin Routes
Route::get('/admin', [OrderController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/orders/print', [OrderController::class, 'print'])->name('admin.orders.print');
Route::post('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');
