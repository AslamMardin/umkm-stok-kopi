<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * UserController
 * Mengelola akun pengguna internal untuk admin gudang.
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('supplier');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        $roles = [
            'admin_gudang' => 'Admin Gudang',
            'supplier'     => 'Supplier',
            'umkm'         => 'UMKM',
        ];

        return view('user.index', compact('users', 'roles'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('user.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'        => ['required', 'in:admin_gudang,supplier,umkm'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ], [
            'name.required'         => 'Nama pengguna wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email ini sudah digunakan.',
            'role.required'         => 'Role wajib dipilih.',
            'role.in'               => 'Role pengguna tidak valid.',
            'supplier_id.exists'    => 'Supplier tidak ditemukan.',
        ]);

        if ($validated['role'] === 'supplier' && !empty($validated['supplier_id'])) {
            $existing = User::where('supplier_id', $validated['supplier_id'])->exists();
            if ($existing) {
                return back()
                    ->withInput()
                    ->withErrors(['supplier_id' => 'Supplier ini sudah terhubung ke akun user lain.']);
            }
        } else {
            $validated['supplier_id'] = null;
        }

        User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'password'        => Hash::make('password123'),
            'role'            => $validated['role'],
            'supplier_id'     => $validated['supplier_id'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Akun user baru berhasil dibuat.');
    }

    public function show(User $user)
    {
        $roles = [
            'admin_gudang' => 'Admin Gudang',
            'supplier'     => 'Supplier',
            'umkm'         => 'UMKM',
        ];

        return view('user.show', compact('user', 'roles'));
    }

    public function edit(User $user)
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('user.edit', compact('user', 'suppliers'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'    => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'        => ['required', 'in:admin_gudang,supplier,umkm'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ], [
            'name.required'         => 'Nama pengguna wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email ini sudah digunakan.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
            'role.in'               => 'Role pengguna tidak valid.',
            'supplier_id.exists'    => 'Supplier tidak ditemukan.',
        ]);

        if ($validated['role'] === 'supplier' && !empty($validated['supplier_id'])) {
            $existing = User::where('supplier_id', $validated['supplier_id'])
                ->where('id', '!=', $user->id)
                ->exists();
            if ($existing) {
                return back()
                    ->withInput()
                    ->withErrors(['supplier_id' => 'Supplier ini sudah terhubung ke akun user lain.']);
            }
        } else {
            $validated['supplier_id'] = null;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->supplier_id = $validated['supplier_id'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Akun user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Akun user berhasil dihapus.');
    }
}
