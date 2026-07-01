<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// ════════════════════════════════════════════════════════
//  ROUTE PUBLIK — dapat diakses tanpa login
// ════════════════════════════════════════════════════════

// Redirect root ke login atau dashboard (tergantung status auth)
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

// Autentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('/login',  'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.post');
});

// ════════════════════════════════════════════════════════
//  ROUTE TERPROTEKSI — hanya bisa diakses setelah login
//  Menggunakan middleware 'auth' bawaan Laravel
// ════════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {

    // ── Logout ────────────────────────────────────────────
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Dashboard (Dapat diakses semua role, tampilan disaring controller) ──
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Route khusus UMKM ──────────────────────────────────
    Route::middleware('role:umkm')->group(function () {
        Route::post('/umkm/beli', [PenjualanController::class, 'beli'])->name('umkm.beli');
    });

    // ── Route khusus Admin Gudang ──────────────────────────
    Route::middleware('role:admin_gudang')->group(function () {
        // ── Barang (CRUD lengkap) ──────────────────────────────
        Route::resource('barang', BarangController::class);

        // ── Supplier (CRUD lengkap) ────────────────────────────
        Route::resource('supplier', SupplierController::class);

        // ── Pembelian ──────────────────────────────────────────
        Route::resource('pembelian', PembelianController::class)
             ->except(['edit', 'update']);

        // ── Produksi ───────────────────────────────────────────
        Route::resource('produksi', ProduksiController::class)
             ->only(['index', 'create', 'store', 'show']);

        // ── Penjualan (Gudang/Admin CRUD) ──────────────────────
        Route::resource('penjualan', PenjualanController::class)
             ->except(['edit', 'update']);

        // ── Laporan ───────────────────────────────────────────
        Route::prefix('laporan')->name('laporan.')->controller(LaporanController::class)->group(function () {
            Route::get('/',         'index')->name('index');
            Route::get('/stok',     'stok')->name('stok');
            Route::get('/pembelian','pembelian')->name('pembelian');
            Route::get('/penjualan','penjualan')->name('penjualan');
            Route::get('/produksi', 'produksi')->name('produksi');

            // ── Print / PDF ─────────────────────────────────
            Route::get('/stok/print',     'printStok')->name('stok.print');
            Route::get('/pembelian/print','printPembelian')->name('pembelian.print');
            Route::get('/penjualan/print','printPenjualan')->name('penjualan.print');
            Route::get('/produksi/print', 'printProduksi')->name('produksi.print');
        });
    });

});
