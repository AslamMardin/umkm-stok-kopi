@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('page-title', 'Tambah Supplier Baru')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Supplier</div>
            <a href="{{ route('supplier.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Supplier <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="Nama perusahaan / toko">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                           placeholder="08xxxxxxxxxx">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="supplier@email.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" rows="3"
                              class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                              placeholder="Alamat lengkap supplier">{{ old('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary">💾 Simpan Supplier</button>
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
