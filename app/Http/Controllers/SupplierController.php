<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'email'   => ['nullable', 'email', 'max:255', 'unique:suppliers,email', 'unique:users,email'],
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'name.unique'   => 'Supplier dengan nama ini sudah terdaftar.',
            'email.email'   => 'Format email tidak valid.',
            'email.unique'  => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $supplier = Supplier::create([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'email'   => $validated['email'] ?? null,
        ]);

        $message = 'Supplier "' . $validated['name'] . '" berhasil ditambahkan.';

        if (!empty($validated['email'])) {
            User::create([
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'password'       => Hash::make('password123'),
                'role'           => 'supplier',
                'supplier_id'    => $supplier->id,
                'email_verified_at' => now(),
            ]);

            $message .= ' Akun login supplier dibuat dengan email ' . $validated['email'] . ' dan password default password123.';
        }

        return redirect()->route('supplier.index')
            ->with('success', $message);
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['pembelians.barang']);
        $totalPembelian = $supplier->pembelians()->sum(\DB::raw('qty * harga_satuan'));

        return view('supplier.show', compact('supplier', 'totalPembelian'));
    }

    public function edit(Supplier $supplier)
    {
        // Ambil user ber-role supplier yang belum ditautkan atau sudah ditautkan ke supplier ini
        $userSuppliers = User::where('role', 'supplier')
            ->where(function ($q) use ($supplier) {
                $q->whereNull('supplier_id')
                  ->orWhere('supplier_id', $supplier->id);
            })
            ->orderBy('name')
            ->get();

        // User yang saat ini sudah terhubung ke supplier ini
        $userTerhubung = User::where('supplier_id', $supplier->id)->first();

        return view('supplier.edit', compact('supplier', 'userSuppliers', 'userTerhubung'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $linkedUser = User::where('supplier_id', $supplier->id)->first();

        $emailRules = ['nullable', 'email', 'max:255', 'unique:suppliers,email,' . $supplier->id];
        if ($linkedUser) {
            $emailRules[] = 'unique:users,email,' . $linkedUser->id;
        } else {
            $emailRules[] = 'unique:users,email';
        }

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:suppliers,name,' . $supplier->id],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'email'   => $emailRules,
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $supplier->update([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'address' => $validated['address'],
            'email'   => $validated['email'],
        ]);

        if (!empty($validated['email']) && $linkedUser) {
            $linkedUser->update([
                'email' => $validated['email'],
                'name'  => $validated['name'],
            ]);
        }

        // Tautkan akun user ke supplier ini
        if (!empty($validated['user_id'])) {
            // Lepas tautan lama jika ada user lain yang sebelumnya ditautkan ke supplier ini
            User::where('supplier_id', $supplier->id)
                ->where('id', '!=', $validated['user_id'])
                ->update(['supplier_id' => null]);

            // Tautkan user baru ke supplier ini
            $newLinkedUser = User::find($validated['user_id']);
            if ($newLinkedUser) {
                $newLinkedUser->update(['supplier_id' => $supplier->id]);

                if (!empty($validated['email'])) {
                    $newLinkedUser->update([
                        'email' => $validated['email'],
                        'name'  => $validated['name'],
                    ]);
                }
            }
        } else {
            // Jika kosong (tidak dipilih), lepas semua tautan user dari supplier ini
            User::where('supplier_id', $supplier->id)->update(['supplier_id' => null]);
        }

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
