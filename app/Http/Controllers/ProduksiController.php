<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ProduksiController
 * Mengelola proses produksi: konversi bahan mentah → produk jadi.
 *
 * INTI LOGIKA SCM:
 * - Stok bahan mentah (raw_material) dikurangi sebesar qty_bahan_mentah
 * - Stok produk jadi (finished_goods) ditambah sebesar qty_produk_jadi
 * - Seluruh operasi dibungkus dalam DB transaction untuk konsistensi data
 */
class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Produksi::with(['bahanMentah', 'produkJadi']);

        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        $produksis = $query->latest('tanggal')->paginate(10)->withQueryString();

        return view('produksi.index', compact('produksis'));
    }

    public function create()
    {
        $bahanMentahs = Barang::bahanMentah()->where('stock', '>', 0)->orderBy('name')->get();
        $produkJadis  = Barang::produkJadi()->orderBy('name')->get();

        return view('produksi.create', compact('bahanMentahs', 'produkJadis'));
    }

    /**
     * Proses produksi baru.
     * Validasi stok → kurangi bahan mentah → tambah produk jadi → catat log.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_bahan_mentah_id' => ['required', 'exists:barangs,id'],
            'barang_produk_jadi_id'  => ['required', 'exists:barangs,id', 'different:barang_bahan_mentah_id'],
            'tanggal'                => ['required', 'date'],
            'qty_bahan_mentah'       => ['required', 'integer', 'min:1'],
            'qty_produk_jadi'        => ['required', 'integer', 'min:1'],
            'keterangan'             => ['nullable', 'string', 'max:500'],
        ], [
            'barang_bahan_mentah_id.required'  => 'Bahan mentah wajib dipilih.',
            'barang_bahan_mentah_id.exists'    => 'Bahan mentah tidak ditemukan.',
            'barang_produk_jadi_id.required'   => 'Produk jadi wajib dipilih.',
            'barang_produk_jadi_id.different'  => 'Produk jadi tidak boleh sama dengan bahan mentah.',
            'tanggal.required'                 => 'Tanggal produksi wajib diisi.',
            'qty_bahan_mentah.required'        => 'Jumlah bahan mentah wajib diisi.',
            'qty_bahan_mentah.min'             => 'Jumlah bahan mentah minimal 1.',
            'qty_produk_jadi.required'         => 'Jumlah produk jadi wajib diisi.',
            'qty_produk_jadi.min'              => 'Jumlah produk jadi minimal 1.',
        ]);

        // Ambil barang bahan mentah untuk cek stok
        $bahanMentah = Barang::findOrFail($validated['barang_bahan_mentah_id']);
        $produkJadi  = Barang::findOrFail($validated['barang_produk_jadi_id']);

        // Validasi tipe barang
        if ($bahanMentah->type !== 'bahan_mentah') {
            return back()->withErrors(['barang_bahan_mentah_id' => 'Barang yang dipilih bukan bahan mentah.'])->withInput();
        }

        if ($produkJadi->type !== 'produk_jadi') {
            return back()->withErrors(['barang_produk_jadi_id' => 'Barang yang dipilih bukan produk jadi.'])->withInput();
        }

        // Validasi kecukupan stok bahan mentah
        if ($bahanMentah->stock < $validated['qty_bahan_mentah']) {
            return back()
                ->withErrors([
                    'qty_bahan_mentah' => "Stok bahan mentah tidak mencukupi. Stok saat ini: {$bahanMentah->stock} {$bahanMentah->satuan}.",
                ])
                ->withInput();
        }

        // Eksekusi dalam satu transaksi database
        DB::transaction(function () use ($validated, $bahanMentah, $produkJadi) {
            // 1. Catat record produksi
            Produksi::create($validated);

            // 2. Kurangi stok bahan mentah
            $bahanMentah->decrement('stock', $validated['qty_bahan_mentah']);

            // 3. Tambah stok produk jadi
            $produkJadi->increment('stock', $validated['qty_produk_jadi']);
        });

        return redirect()->route('produksi.index')
            ->with('success', "Produksi berhasil dicatat. {$validated['qty_bahan_mentah']} {$bahanMentah->satuan} {$bahanMentah->name} → {$validated['qty_produk_jadi']} {$produkJadi->satuan} {$produkJadi->name}.");
    }

    public function show(Produksi $produksi)
    {
        $produksi->load(['bahanMentah', 'produkJadi']);
        return view('produksi.show', compact('produksi'));
    }
}
