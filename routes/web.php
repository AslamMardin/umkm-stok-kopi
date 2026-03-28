<?php
// =============================================================
// FILE: routes/web.php
// =============================================================

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianBahanBakuController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\StokController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SCM UMKM Kopi Polewali Mandar - Web Routes
|--------------------------------------------------------------------------
*/




Route::get('/', fn() => redirect()->route('dashboard'));

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -------------------------------------------------------
    // Pembelian Bahan Baku
    // -------------------------------------------------------
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::get('/',           [PembelianBahanBakuController::class, 'index'])   ->name('index');
        Route::get('/create',     [PembelianBahanBakuController::class, 'create'])  ->name('create');
        Route::post('/',          [PembelianBahanBakuController::class, 'store'])   ->name('store');
        Route::get('/{pembelian}',[PembelianBahanBakuController::class, 'show'])    ->name('show');

        // Aksi yang mempengaruhi stok
        Route::post('/{pembelian}/terima',   [PembelianBahanBakuController::class, 'terima'])  ->name('terima');
        Route::post('/{pembelian}/batalkan', [PembelianBahanBakuController::class, 'batalkan'])->name('batalkan');
    });

    // -------------------------------------------------------
    // Produksi (Roasting & Packing)
    // -------------------------------------------------------
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('/',             [ProduksiController::class, 'index'])     ->name('index');
        Route::get('/create',       [ProduksiController::class, 'create'])    ->name('create');
        Route::post('/',            [ProduksiController::class, 'store'])     ->name('store');
        Route::get('/{produksi}',   [ProduksiController::class, 'show'])      ->name('show');

        // Selesaikan produksi → update stok otomatis
        Route::post('/{produksi}/selesaikan', [ProduksiController::class, 'selesaikan'])->name('selesaikan');
    });

    // -------------------------------------------------------
    // Penjualan Produk
    // -------------------------------------------------------
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('/',              [PenjualanController::class, 'index'])     ->name('index');
        Route::get('/create',        [PenjualanController::class, 'create'])    ->name('create');
        Route::post('/',             [PenjualanController::class, 'store'])     ->name('store');
        Route::get('/{penjualan}',   [PenjualanController::class, 'show'])      ->name('show');

        // Konfirmasi & batalkan penjualan → update stok otomatis
        Route::post('/{penjualan}/konfirmasi', [PenjualanController::class, 'konfirmasi'])->name('konfirmasi');
        Route::post('/{penjualan}/batalkan',   [PenjualanController::class, 'batalkan'])  ->name('batalkan');
    });

    // -------------------------------------------------------
    // Manajemen Stok (read-only monitoring)
    // -------------------------------------------------------
    Route::prefix('stok')->name('stok.')->group(function () {
        Route::get('/', [StokController::class, 'index'])->name('index');
        Route::get('/bahan-baku/{bahanBaku}', [StokController::class, 'bahanBaku'])->name('bahan-baku');
        Route::get('/produk/{produkKopi}',    [StokController::class, 'produkKopi'])->name('produk');
    });
});

