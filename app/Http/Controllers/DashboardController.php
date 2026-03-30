<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\Produksi;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController
 * Menampilkan ringkasan statistik utama SCM ke halaman dashboard.
 */
class DashboardController extends Controller
{
    /**
     * Kumpulkan semua data ringkasan dan kirim ke view dashboard.
     */
    public function index()
    {
        // Statistik stok barang
        $totalBahanMentah  = Barang::bahanMentah()->count();
        $totalProdukJadi   = Barang::produkJadi()->count();
        $stokRendah        = Barang::where('stock', '<=', 10)->count(); // threshold stok rendah

        // Statistik transaksi
        $totalSupplier     = Supplier::count();
        $totalPembelian    = Pembelian::count();
        $totalProduksi     = Produksi::count();
        $totalPenjualan    = Penjualan::count();

        // Nilai keuangan ringkas
        $totalNilaiPembelian = Pembelian::sum(DB::raw('qty * harga_satuan'));
        $totalNilaiPenjualan = Penjualan::sum(DB::raw('qty * harga_satuan'));

        // 5 transaksi penjualan terbaru
        $penjualanTerbaru = Penjualan::with('barang')
            ->latest()
            ->take(5)
            ->get();

        // 5 transaksi pembelian terbaru
        $pembelianTerbaru = Pembelian::with(['supplier', 'barang'])
            ->latest()
            ->take(5)
            ->get();

        // Barang dengan stok menipis (≤ 10)
        $barangStokRendah = Barang::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBahanMentah',
            'totalProdukJadi',
            'stokRendah',
            'totalSupplier',
            'totalPembelian',
            'totalProduksi',
            'totalPenjualan',
            'totalNilaiPembelian',
            'totalNilaiPenjualan',
            'penjualanTerbaru',
            'pembelianTerbaru',
            'barangStokRendah',
        ));
    }
}
