<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProductStoreController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'index'])->name('index');
Route::get('/', [ProductStoreController::class, 'index'])->name('landing.index');

Route::get('/dashboard/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/dashboard/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');

Route::post('/dashboard/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('dashboard')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'admin'])->name('dashboard');

    Route::resource('products', ProductController::class)->except(['show']);
});

Route::get('/dashboard/products/filter', [ProductController::class, 'filter'])->name('admin.products.filter');