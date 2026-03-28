<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\DetailPembelianBahanBaku;
use App\Models\PembelianBahanBaku;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanBakuController extends Controller
{
    public function index()
    {
        $pembelians = PembelianBahanBaku::with('supplier')
            ->latest()
            ->paginate(15);

        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers  = Supplier::orderBy('nama_supplier')->get();
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();

        return view('pembelian.create', compact('suppliers', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'            => 'required|exists:suppliers,id',
            'tanggal_beli'           => 'required|date',
            'catatan'                => 'nullable|string',
            'items'                  => 'required|array|min:1',
            'items.*.bahan_baku_id'  => 'required|exists:bahan_bakus,id',
            'items.*.jumlah'         => 'required|numeric|min:0.01',
            'items.*.harga_satuan'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $noPembelian = 'PB-' . date('Ymd') . '-' . str_pad(
                PembelianBahanBaku::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $pembelian = PembelianBahanBaku::create([
                'no_pembelian' => $noPembelian,
                'supplier_id'  => $request->supplier_id,
                'tanggal_beli' => $request->tanggal_beli,
                'total_harga'  => 0,
                'status'       => 'pending',
                'catatan'      => $request->catatan,
                'user_id'      => auth()->id(),
            ]);

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                DetailPembelianBahanBaku::create([
                    'pembelian_id'  => $pembelian->id,
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'jumlah'        => $item['jumlah'],
                    'harga_satuan'  => $item['harga_satuan'],
                    'subtotal'      => $subtotal,
                ]);
                $totalHarga += $subtotal;
            }

            $pembelian->update(['total_harga' => $totalHarga]);
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian bahan baku berhasil dicatat.');
    }

    public function show(PembelianBahanBaku $pembelian)
    {
        $pembelian->load('supplier', 'detailPembelians.bahanBaku', 'user');
        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Konfirmasi penerimaan barang → stok bahan baku bertambah otomatis
     */
    public function terima(PembelianBahanBaku $pembelian)
    {
        if ($pembelian->status !== 'pending') {
            return back()->with('error', 'Pembelian ini tidak dapat diubah statusnya.');
        }

        DB::transaction(function () use ($pembelian) {
            $pembelian->terimaPembelian();
        });

        return redirect()->route('pembelian.show', $pembelian)
            ->with('success', 'Pembelian dikonfirmasi. Stok bahan baku telah diperbarui.');
    }

    /**
     * Batalkan pembelian (hanya saat masih pending)
     */
    public function batalkan(PembelianBahanBaku $pembelian)
    {
        if ($pembelian->status !== 'pending') {
            return back()->with('error', 'Hanya pembelian berstatus "pending" yang dapat dibatalkan.');
        }

        $pembelian->update(['status' => 'dibatalkan']);

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dibatalkan.');
    }
}
