<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

/**
 * SupplierController
 * CRUD lengkap untuk manajemen data supplier/pemasok bahan mentah.
 */
class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('pembelians');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:suppliers,name'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'email'   => ['nullable', 'email', 'max:255'],
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'name.unique'   => 'Supplier dengan nama ini sudah terdaftar.',
            'email.email'   => 'Format email tidak valid.',
        ]);

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['pembelians.barang']);
        $totalPembelian = $supplier->pembelians()->sum(\DB::raw('qty * harga_satuan'));

        return view('supplier.show', compact('supplier', 'totalPembelian'));
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:suppliers,name,' . $supplier->id],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'email'   => ['nullable', 'email', 'max:255'],
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier "' . $supplier->name . '" berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        // if ($supplier->pembelians()->exists()) {
        //     return back()->with('error', 'Supplier tidak dapat dihapus karena masih memiliki riwayat pembelian.');
        // }

        $name = $supplier->name;
        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier "' . $name . '" berhasil dihapus.');
    }
}
