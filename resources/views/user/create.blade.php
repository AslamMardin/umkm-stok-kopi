@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div style="max-width:700px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Tambah User</div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <div class="form-label">Password</div>
                    <div class="form-hint">Password default untuk akun baru adalah <strong>password123</strong>.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin_gudang" {{ old('role') == 'admin_gudang' ? 'selected' : '' }}>Admin Gudang</option>
                        <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="umkm" {{ old('role') == 'umkm' ? 'selected' : '' }}>UMKM</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tautkan Supplier</label>
                    <select name="supplier_id" class="form-control {{ $errors->has('supplier_id') ? 'is-invalid' : '' }}">
                        <option value="">— Pilih Supplier (opsional) —</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
