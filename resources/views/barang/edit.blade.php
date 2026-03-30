@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')

@section('content')

<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Edit: {{ $barang->name }}</div>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.update', $barang) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Barang <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $barang->name) }}"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Barang <span class="required">*</span></label>
                    <select name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                        <option value="bahan_mentah" {{ old('type', $barang->type) == 'bahan_mentah' ? 'selected' : '' }}>🌱 Bahan Mentah</option>
                        <option value="produk_jadi"  {{ old('type', $barang->type) == 'produk_jadi'  ? 'selected' : '' }}>📦 Produk Jadi</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Stok <span class="required">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $barang->stock) }}" min="0"
                               class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}">
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-hint">⚠️ Ubah stok langsung di sini hanya untuk koreksi manual.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Satuan <span class="required">*</span></label>
                        <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}"
                               class="form-control {{ $errors->has('satuan') ? 'is-invalid' : '' }}">
                        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary">💾 Update Barang</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
