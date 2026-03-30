@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang Baru')

@section('content')

<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Tambah Barang</div>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Barang <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="Contoh: Biji Kopi Arabika Grade A">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Barang <span class="required">*</span></label>
                    <select name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="bahan_mentah" {{ old('type') == 'bahan_mentah' ? 'selected' : '' }}>
                            🌱 Bahan Mentah
                        </option>
                        <option value="produk_jadi" {{ old('type') == 'produk_jadi' ? 'selected' : '' }}>
                            📦 Produk Jadi
                        </option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Bahan Mentah = bahan baku. Produk Jadi = hasil produksi siap jual.</div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Stok Awal <span class="required">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
                               class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Satuan <span class="required">*</span></label>
                        <input type="text" name="satuan" value="{{ old('satuan') }}"
                               class="form-control {{ $errors->has('satuan') ? 'is-invalid' : '' }}"
                               placeholder="kg, liter, pcs, gram...">
                        @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary">💾 Simpan Barang</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
