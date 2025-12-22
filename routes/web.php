<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| REDIRECT LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login.form');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| AUTH (Login & Logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| PRODUK
|--------------------------------------------------------------------------
*/
Route::prefix('produk')->name('produk.')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| TRANSAKSI
|--------------------------------------------------------------------------
*/
Route::prefix('transaksi')->name('transaksi.')->middleware('auth')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/add/{id}', [TransactionController::class, 'addToCart'])->name('add');
    Route::post('/remove/{id}', [TransactionController::class, 'removeFromCart'])->name('remove');
    Route::post('/update-cart', [TransactionController::class, 'updateCart'])->name('updateCart');
    Route::post('/cart-total', [TransactionController::class, 'cartTotal'])->name('cartTotal');
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');

    Route::prefix('open-bill')->name('openBill.')->group(function () {
        // Halaman daftar transaksi tersimpan
        Route::get('/', [TransactionController::class, 'openBillPage'])->name('page');
        Route::post('/store', [TransactionController::class, 'saveOpenBill'])->name('store');
        Route::get('/list', [TransactionController::class, 'getOpenBills'])->name('list');
        Route::get('/load/{id}', [TransactionController::class, 'loadOpenBill'])->name('load');
        Route::delete('/delete/{id}', [TransactionController::class, 'deleteOpenBill'])->name('destroy');
    });

    Route::get('/receipt/{id}', [TransactionController::class, 'printReceipt'])->name('receipt');
    Route::post('/{id}/cancel', [TransactionController::class, 'cancelTransaction'])->name('cancel');
    Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| RIWAYAT TRANSAKSI
|--------------------------------------------------------------------------
*/
Route::get('/riwayat', [TransactionController::class, 'history'])
    ->middleware('auth')
    ->name('riwayat.index');

/*

/*
|--------------------------------------------------------------------------
| LAPORAN (Owner)
|--------------------------------------------------------------------------
*/
Route::prefix('laporan')->name('laporan.')->middleware('auth')->group(function(){
    Route::get('/penjualan', [ReportController::class, 'ownerSales'])->name('penjualan');
});

/*
|--------------------------------------------------------------------------
| KAS (Opening & Closing)
|--------------------------------------------------------------------------
*/
Route::prefix('kas')->name('kas.')->middleware('auth')->group(function () {
    Route::get('/opening', [CashController::class, 'openingForm'])->name('opening.form');
    Route::post('/opening', [CashController::class, 'saveOpening'])->name('opening.store');
    Route::get('/closing', [CashController::class, 'closingForm'])->name('closing.form');
    Route::post('/closing', [CashController::class, 'saveClosing'])->name('closing.store');
    Route::get('/riwayat', [CashController::class, 'history'])->name('history');
    Route::get('/riwayat/{id}', [CashController::class, 'show'])->name('show');
    Route::get('/print/{id}', [CashController::class, 'print'])->name('print');
});

/*
|--------------------------------------------------------------------------
| MANAJEMEN KARYAWAN (Tanpa role)
|--------------------------------------------------------------------------
*/
Route::prefix('karyawan')->name('karyawan.')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
});
