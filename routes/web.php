<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PatnerProductController;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\VerificationPartnerController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::prefix('dashboard')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'adminLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('dashboard')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'admin'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
});
Route::get('/dashboard/products/filter', [AdminProductController::class, 'filter'])->name('admin.products.filter')->middleware('throttle:60,1');
Route::get('/dashboard/users', [AdminController::class, 'users'])->name('admin.users.index')->middleware('auth', 'admin');
Route::get('dashboard/partner', [AdminController::class, 'partners'])->name('admin.partner.index')->middleware('auth', 'admin');
Route::get('/dashboard/partner-verification', [AdminController::class, 'partnerVerification'])->name('admin.partner.verification'); 
Route::middleware(['auth', 'admin'])->prefix('dashboard/partner-verification')->name('admin.partner.verification.')->group(function () {
    Route::get('/', [AdminController::class, 'partnerVerification'])->name('index');
    Route::get('verify/{partner}', [VerificationPartnerController::class, 'showVerification'])->name('show');
    Route::post('verify/{partner}', [VerificationPartnerController::class, 'processDecision'])->name('decide');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/media/ktp/{id}', [VerificationPartnerController::class, 'show'])
        ->name('ktp.show');
});  

// Index Route
Route::get('/', [PagesController::class, 'index'])->name('index');
Route::get('/login', [AuthController::class, 'userLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [PagesController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [PagesController::class, 'products'])->name('index');
    Route::get('/{product}', [ProductController::class, 'showDetails'])->name('details');
});
Route::prefix('cart')->name('cart.')->group(function () {
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{itemId}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('remove');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
});

Route::middleware(['auth'])->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/process', [CartController::class, 'processCheckout'])->name('process');
});

Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook');

Route::middleware(['partner'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/', [PagesController::class, 'partner'])->name('dashboard');
    Route::resource('products', PatnerProductController::class)->except(['show']);
});
Route::get('/partner/{partner}/identity', [AuthController::class, 'servePartnerIdentity'])
    ->name('partner.identity')
    ->middleware('auth');
Route::get('/partner-register', [PagesController::class, 'becomePartner'])->name('partner.register');
Route::post('/partner-register', [AuthController::class, 'partnerRegister'])->name('partner.register.submit');
Route::get('/profile-settings', [PagesController::class, 'profileSettings'])->name('profile.settings');
Route::get('/my-orders', [PagesController::class, 'myOrders'])->name('my.orders');

Route::get('api/rajaongkir/location',[RajaOngkirController::class, 'getLocation'])->name('api.rajaongkir.location')->middleware('throttle:60,1');
Route::get('api/rajaongkir/cost',[RajaOngkirController::class, 'getCost'])->name('api.rajaongkir.cost')->middleware('throttle:60,1');
Route::post('/rajaongkir/calculate-shipping', [RajaOngkirController::class, 'calculateShippingByPostalCode'])
    ->name('rajaongkir.calculate.shipping');