<?php

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
