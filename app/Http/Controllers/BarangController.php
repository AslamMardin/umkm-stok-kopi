<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

/**
 * BarangController
 * CRUD lengkap untuk manajemen barang (bahan mentah & produk jadi).
 */
class BarangController extends Controller
{
    /**
     * Tampilkan daftar semua barang dengan filter opsional.
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        // Filter berdasarkan tipe
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan nama (pencarian)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $barangs = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('barang.index', compact('barangs'));
    }

    /**
     * Tampilkan form tambah barang baru.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Simpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:barangs,name'],
            'type'  => ['required', 'in:bahan_mentah,produk_jadi'],
            'stock' => ['required', 'integer', 'min:0'],
            'satuan'=> ['required', 'string', 'max:50'],
        ], [
            'name.required'  => 'Nama barang wajib diisi.',
            'name.unique'    => 'Nama barang sudah terdaftar.',
            'type.required'  => 'Tipe barang wajib dipilih.',
            'type.in'        => 'Tipe barang tidak valid.',
            'stock.required' => 'Stok awal wajib diisi.',
            'stock.min'      => 'Stok tidak boleh negatif.',
            'satuan.required'=> 'Satuan wajib diisi.',
        ]);

        Barang::create($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail satu barang beserta riwayat transaksinya.
     */
    public function show(Barang $barang)
    {
        // Muat relasi untuk riwayat transaksi
        $barang->load([
            'pembelians.supplier',
            'penjualans',
            'produksiSebagaiBahanMentah',
            'produksiSebagaiProdukJadi',
        ]);

        return view('barang.show', compact('barang'));
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update data barang di database.
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:barangs,name,' . $barang->id],
            'type'  => ['required', 'in:bahan_mentah,produk_jadi'],
            'stock' => ['required', 'integer', 'min:0'],
            'satuan'=> ['required', 'string', 'max:50'],
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang "' . $barang->name . '" berhasil diperbarui.');
    }

    /**
     * Hapus barang dari database.
     * Mencegah penghapusan jika masih ada transaksi terkait.
     */
    public function destroy(Barang $barang)
    {
        // Cek apakah masih ada relasi transaksi
        // if ($barang->pembelians()->exists() || $barang->penjualans()->exists()) {
        //     return back()->with('error', 'Barang tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        // }

        $name = $barang->name;
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang "' . $name . '" berhasil dihapus.');
    }
}
