<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * PembelianController
 * Mengelola pembelian bahan mentah dari supplier.
 * Setiap pembelian otomatis menambah stok barang terkait.
 */
class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'barang']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        $pembelians = $query->latest('tanggal')->paginate(10)->withQueryString();
        $suppliers  = Supplier::orderBy('name')->get();

        return view('pembelian.index', compact('pembelians', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        // Hanya bahan mentah yang bisa dibeli dari supplier
        $barangs   = Barang::bahanMentah()->orderBy('name')->get();

        return view('pembelian.create', compact('suppliers', 'barangs'));
    }

    /**
     * Simpan pembelian baru & tambah stok barang (dalam 1 DB transaction).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'  => ['required', 'exists:suppliers,id'],
            'barang_id'    => ['required', 'exists:barangs,id'],
            'tanggal'      => ['required', 'date'],
            'qty'          => ['required', 'integer', 'min:1'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'keterangan'   => ['nullable', 'string', 'max:500'],
        ], [
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists'   => 'Supplier tidak ditemukan.',
            'barang_id.required'   => 'Barang wajib dipilih.',
            'barang_id.exists'     => 'Barang tidak ditemukan.',
            'tanggal.required'     => 'Tanggal pembelian wajib diisi.',
            'qty.required'         => 'Jumlah wajib diisi.',
            'qty.min'              => 'Jumlah minimal 1.',
            'harga_satuan.required'=> 'Harga satuan wajib diisi.',
            'harga_satuan.min'     => 'Harga tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($validated) {
            // Simpan record pembelian
            Pembelian::create($validated);

            // Tambah stok barang
            $barang = Barang::findOrFail($validated['barang_id']);
            $barang->increment('stock', $validated['qty']);
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dicatat. Stok barang telah diperbarui.');
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['supplier', 'barang']);
        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Hapus pembelian & kembalikan stok (rollback stok).
     */
    public function destroy(Pembelian $pembelian)
    {
        DB::transaction(function () use ($pembelian) {
            // Kurangi kembali stok yang sudah ditambahkan
            $barang = $pembelian->barang;
            if ($barang->stock < $pembelian->qty) {
                throw new \Exception('Stok tidak mencukupi untuk membatalkan pembelian ini.');
            }
            $barang->decrement('stock', $pembelian->qty);
            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dihapus dan stok telah dikembalikan.');
    }
}
