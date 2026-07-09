@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div style="max-width:700px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Edit: {{ $user->name }}</div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password"
                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin_gudang" {{ old('role', $user->role) == 'admin_gudang' ? 'selected' : '' }}>Admin Gudang</option>
                        <option value="supplier" {{ old('role', $user->role) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="umkm" {{ old('role', $user->role) == 'umkm' ? 'selected' : '' }}>UMKM</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tautkan Supplier</label>
                    <select name="supplier_id" class="form-control {{ $errors->has('supplier_id') ? 'is-invalid' : '' }}">
                        <option value="">— Pilih Supplier (opsional) —</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $user->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color:var(--caramel);font-size:12px;display:block;margin-top:4px;">
                        Hanya dipakai jika role user adalah Supplier. Jika dipilih, akun akan terhubung ke data supplier yang sama.
                    </small>
                    @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
