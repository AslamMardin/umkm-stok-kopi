<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * PenjualanController
 * Mencatat penjualan produk jadi ke konsumen.
 * Setiap penjualan mengurangi stok produk jadi.
 */
class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('barang');

        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        $penjualans = $query->latest('tanggal')->paginate(10)->withQueryString();

        // Summary statistik
        $totalPendapatan = $query->sum(DB::raw('qty * harga_satuan'));

        return view('penjualan.index', compact('penjualans', 'totalPendapatan'));
    }

    public function create()
    {
        // Hanya produk jadi yang bisa dijual, dan hanya yang stoknya ada
        $barangs = Barang::produkJadi()->where('stock', '>', 0)->orderBy('name')->get();

        return view('penjualan.create', compact('barangs'));
    }

    /**
     * Simpan penjualan baru & kurangi stok produk jadi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'    => ['required', 'exists:barangs,id'],
            'tanggal'      => ['required', 'date'],
            'qty'          => ['required', 'integer', 'min:1'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'pembeli'      => ['nullable', 'string', 'max:255'],
            'keterangan'   => ['nullable', 'string', 'max:500'],
        ], [
            'barang_id.required'   => 'Barang wajib dipilih.',
            'tanggal.required'     => 'Tanggal penjualan wajib diisi.',
            'qty.required'         => 'Jumlah wajib diisi.',
            'qty.min'              => 'Jumlah minimal 1.',
            'harga_satuan.required'=> 'Harga satuan wajib diisi.',
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);

        // Validasi tipe barang
        if ($barang->type !== 'produk_jadi') {
            return back()->withErrors(['barang_id' => 'Hanya produk jadi yang dapat dijual.'])->withInput();
        }

        // Validasi kecukupan stok
        if ($barang->stock < $validated['qty']) {
            return back()
                ->withErrors([
                    'qty' => "Stok tidak mencukupi. Stok saat ini: {$barang->stock} {$barang->satuan}.",
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $barang) {
            Penjualan::create($validated);
            $barang->decrement('stock', $validated['qty']);
        });

        return redirect()->route('penjualan.index')
            ->with('success', 'Penjualan berhasil dicatat. Stok telah dikurangi.');
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load('barang');
        return view('penjualan.show', compact('penjualan'));
    }

    public function destroy(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            // Kembalikan stok
            $penjualan->barang->increment('stock', $penjualan->qty);
            $penjualan->delete();
        });

        return redirect()->route('penjualan.index')
            ->with('success', 'Penjualan berhasil dihapus dan stok telah dikembalikan.');
    }
}
