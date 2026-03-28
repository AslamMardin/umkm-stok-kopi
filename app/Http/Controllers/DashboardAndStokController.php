<?php

// =============================================================
// FILE: app/Http/Controllers/DashboardController.php
// =============================================================
namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PembelianBahanBaku;
use App\Models\Penjualan;
use App\Models\Produksi;
use App\Models\ProdukKopi;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan stok
        $totalBahanBaku      = BahanBaku::count();
        $bahanBakuKritis     = BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count();
        $totalProduk         = ProdukKopi::count();
        $produkStokKritis    = ProdukKopi::whereColumn('stok', '<=', 'stok_minimum')->count();

        // Ringkasan transaksi bulan ini
        $pembelianBulanIni   = PembelianBahanBaku::whereMonth('tanggal_beli', now()->month)
                                ->whereYear('tanggal_beli', now()->year)
                                ->where('status', 'diterima')
                                ->sum('total_harga');

        $penjualanBulanIni   = Penjualan::whereMonth('tanggal_jual', now()->month)
                                ->whereYear('tanggal_jual', now()->year)
                                ->where('status', 'lunas')
                                ->sum('total_bayar');

        $produksiAktif       = Produksi::where('status', 'proses')->count();

        // Daftar bahan baku kritis
        $bahanBakuKritisList = BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->get();
        $produkKritisList    = ProdukKopi::whereColumn('stok', '<=', 'stok_minimum')->get();

        // Transaksi terbaru
        $pembelianTerbaru    = PembelianBahanBaku::with('supplier')->latest()->take(5)->get();
        $penjualanTerbaru    = Penjualan::with('pelanggan')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalBahanBaku', 'bahanBakuKritis', 'totalProduk', 'produkStokKritis',
            'pembelianBulanIni', 'penjualanBulanIni', 'produksiAktif',
            'bahanBakuKritisList', 'produkKritisList',
            'pembelianTerbaru', 'penjualanTerbaru'
        ));
    }
}

// =============================================================
// FILE: app/Http/Controllers/StokController.php
// =============================================================
namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\ProdukKopi;

class StokController extends Controller
{
    /**
     * Tampilkan ringkasan semua stok bahan baku dan produk jadi
     */
    public function index()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        $produkList = ProdukKopi::orderBy('nama_produk')->get();

        return view('stok.index', compact('bahanBakus', 'produkList'));
    }

    /**
     * Detail riwayat stok bahan baku tertentu
     */
    public function bahanBaku(BahanBaku $bahanBaku)
    {
        $pembelians = $bahanBaku->detailPembelians()
            ->with('pembelian.supplier')
            ->latest()
            ->paginate(10);

        $produksis = $bahanBaku->detailProduksis()
            ->with('produksi')
            ->latest()
            ->paginate(10);

        return view('stok.bahan-baku-detail', compact('bahanBaku', 'pembelians', 'produksis'));
    }

    /**
     * Detail riwayat stok produk jadi tertentu
     */
    public function produkKopi(ProdukKopi $produkKopi)
    {
        $produksis = $produkKopi->detailProduksis()
            ->with('produksi')
            ->latest()
            ->paginate(10);

        $penjualans = $produkKopi->detailPenjualans()
            ->with('penjualan.pelanggan')
            ->latest()
            ->paginate(10);

        return view('stok.produk-detail', compact('produkKopi', 'produksis', 'penjualans'));
    }
}
