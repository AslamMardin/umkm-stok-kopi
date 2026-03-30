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

    // ── Dashboard ─────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Barang (CRUD lengkap) ──────────────────────────────
    Route::resource('barang', BarangController::class);

    // ── Supplier (CRUD lengkap) ────────────────────────────
    Route::resource('supplier', SupplierController::class);

    // ── Pembelian ──────────────────────────────────────────
    // GET    /pembelian          → index
    // GET    /pembelian/create   → create
    // POST   /pembelian          → store
    // GET    /pembelian/{id}     → show
    // DELETE /pembelian/{id}     → destroy
    Route::resource('pembelian', PembelianController::class)
         ->except(['edit', 'update']); // Pembelian tidak bisa diedit, hanya dihapus + buat baru

    // ── Produksi ───────────────────────────────────────────
    Route::resource('produksi', ProduksiController::class)
         ->only(['index', 'create', 'store', 'show']); // Produksi hanya bisa ditambah & dilihat

    // ── Penjualan ──────────────────────────────────────────
    Route::resource('penjualan', PenjualanController::class)
         ->except(['edit', 'update']);

    // ── Laporan ───────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->controller(LaporanController::class)->group(function () {
        Route::get('/',         'index')->name('index');
        Route::get('/stok',     'stok')->name('stok');
        Route::get('/pembelian','pembelian')->name('pembelian');
        Route::get('/penjualan','penjualan')->name('penjualan');
        Route::get('/produksi', 'produksi')->name('produksi');
    });
});
