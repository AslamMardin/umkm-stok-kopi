<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\ProdukKopi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('pelanggan')
            ->latest()
            ->paginate(15);

        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $produkList = ProdukKopi::where('stok', '>', 0)->orderBy('nama_produk')->get();

        return view('penjualan.create', compact('pelanggans', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'           => 'nullable|exists:pelanggans,id',
            'tanggal_jual'           => 'required|date',
            'metode_bayar'           => 'required|in:tunai,transfer,cod',
            'diskon'                 => 'nullable|numeric|min:0',
            'catatan'                => 'nullable|string',
            'items'                  => 'required|array|min:1',
            'items.*.produk_id'      => 'required|exists:produk_kopis,id',
            'items.*.jumlah'         => 'required|integer|min:1',
            'items.*.harga_satuan'   => 'required|numeric|min:0',
            'items.*.diskon_item'    => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $noPenjualan = 'PJL-' . date('Ymd') . '-' . str_pad(
                Penjualan::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $penjualan = Penjualan::create([
                'no_penjualan' => $noPenjualan,
                'pelanggan_id' => $request->pelanggan_id,
                'tanggal_jual' => $request->tanggal_jual,
                'total_harga'  => 0,
                'diskon'       => $request->diskon ?? 0,
                'total_bayar'  => 0,
                'metode_bayar' => $request->metode_bayar,
                'status'       => 'pending',
                'catatan'      => $request->catatan,
                'user_id'      => auth()->id(),
            ]);

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $diskonItem = $item['diskon_item'] ?? 0;
                $subtotal   = ($item['jumlah'] * $item['harga_satuan']) - $diskonItem;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id'    => $item['produk_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'diskon_item'  => $diskonItem,
                    'subtotal'     => $subtotal,
                ]);

                $totalHarga += $subtotal;
            }

            $totalBayar = $totalHarga - ($request->diskon ?? 0);
            $penjualan->update([
                'total_harga' => $totalHarga,
                'total_bayar' => $totalBayar,
            ]);
        });

        return redirect()->route('penjualan.index')
            ->with('success', 'Data penjualan berhasil dicatat. Konfirmasi pembayaran untuk memperbarui stok.');
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load('pelanggan', 'detailPenjualans.produkKopi', 'user');
        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Konfirmasi penjualan lunas → stok produk berkurang otomatis
     */
    public function konfirmasi(Penjualan $penjualan)
    {
        if ($penjualan->status !== 'pending') {
            return back()->with('error', 'Penjualan ini tidak dapat dikonfirmasi.');
        }

        try {
            $penjualan->konfirmasiPenjualan();
            return redirect()->route('penjualan.show', $penjualan)
                ->with('success', 'Penjualan dikonfirmasi lunas. Stok produk telah diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Batalkan penjualan → stok produk dikembalikan
     */
    public function batalkan(Penjualan $penjualan)
    {
        try {
            $penjualan->batalkanPenjualan();
            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan dibatalkan. Stok produk telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
