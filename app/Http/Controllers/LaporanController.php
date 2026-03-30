<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Produksi;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * LaporanController
 * Menghasilkan laporan ringkasan SCM: pembelian, produksi, penjualan, stok.
 */
class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Laporan stok barang saat ini.
     */
    public function stok(Request $request)
    {
        $barangs = Barang::query()
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('laporan.stok', compact('barangs'));
    }

    /**
     * Laporan ringkasan pembelian per periode.
     */
    public function pembelian(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->toDateString());

        $data = Pembelian::with(['supplier', 'barang'])
            ->whereBetween('tanggal', [$from, $to])
            ->latest('tanggal')
            ->get();

        $total = $data->sum(fn($p) => $p->qty * $p->harga_satuan);

        return view('laporan.pembelian', compact('data', 'total', 'from', 'to'));
    }

    /**
     * Laporan ringkasan penjualan per periode.
     */
    public function penjualan(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->toDateString());

        $data = Penjualan::with('barang')
            ->whereBetween('tanggal', [$from, $to])
            ->latest('tanggal')
            ->get();

        $total = $data->sum(fn($p) => $p->qty * $p->harga_satuan);

        // Rekapitulasi per produk
        $rekap = $data->groupBy('barang_id')->map(function ($items) {
            return [
                'barang' => $items->first()->barang->name,
                'total_qty' => $items->sum('qty'),
                'total_nilai' => $items->sum(fn($p) => $p->qty * $p->harga_satuan),
            ];
        })->values();

        return view('laporan.penjualan', compact('data', 'total', 'from', 'to', 'rekap'));
    }

    /**
     * Laporan produksi per periode.
     */
    public function produksi(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->toDateString());

        $data = Produksi::with(['bahanMentah', 'produkJadi'])
            ->whereBetween('tanggal', [$from, $to])
            ->latest('tanggal')
            ->get();

        return view('laporan.produksi', compact('data', 'from', 'to'));
    }
}
