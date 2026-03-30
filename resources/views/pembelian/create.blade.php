@extends('layouts.app')
@section('title', 'Catat Pembelian')
@section('page-title', 'Catat Pembelian Baru')

@section('content')
<div style="max-width:660px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Pembelian Bahan Mentah</div>
            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('pembelian.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Supplier <span class="required">*</span></label>
                    <select name="supplier_id" class="form-control {{ $errors->has('supplier_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-hint">
                        Supplier belum ada? <a href="{{ route('supplier.create') }}" target="_blank" style="color:var(--caramel);">Tambah supplier baru</a>.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Bahan Mentah <span class="required">*</span></label>
                    <select name="barang_id" id="barang_id"
                            class="form-control {{ $errors->has('barang_id') ? 'is-invalid' : '' }}"
                            onchange="updateSatuan(this)">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}"
                                    data-satuan="{{ $b->satuan }}"
                                    data-stok="{{ $b->stock }}"
                                    {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->name }} (Stok: {{ $b->stock }} {{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Pembelian <span class="required">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}">
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Jumlah (Qty) <span class="required">*</span></label>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="number" name="qty" id="qty" value="{{ old('qty') }}" min="1"
                                   class="form-control {{ $errors->has('qty') ? 'is-invalid' : '' }}"
                                   placeholder="0" oninput="hitungTotal()">
                            <span id="satuan-label" style="color:var(--caramel);font-size:13px;white-space:nowrap;">
                                satuan
                            </span>
                        </div>
                        @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Harga Satuan (Rp) <span class="required">*</span></label>
                        <input type="number" name="harga_satuan" id="harga_satuan"
                               value="{{ old('harga_satuan') }}" min="0" step="100"
                               class="form-control {{ $errors->has('harga_satuan') ? 'is-invalid' : '' }}"
                               placeholder="0" oninput="hitungTotal()">
                        @error('harga_satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Total preview --}}
                <div style="background:var(--cream);border-radius:8px;padding:12px 16px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--caramel);font-weight:500;">Total Pembelian:</span>
                    <span id="total-preview" style="font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:var(--roast);">
                        Rp 0
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="form-control"
                              placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
                    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateSatuan(select) {
        const opt = select.options[select.selectedIndex];
        document.getElementById('satuan-label').textContent = opt.dataset.satuan || 'satuan';
        hitungTotal();
    }

    function hitungTotal() {
        const qty   = parseFloat(document.getElementById('qty').value) || 0;
        const harga = parseFloat(document.getElementById('harga_satuan').value) || 0;
        const total = qty * harga;
        document.getElementById('total-preview').textContent =
            'Rp ' + total.toLocaleString('id-ID');
    }

    // Init on page load (untuk old() values)
    document.addEventListener('DOMContentLoaded', function() {
        const sel = document.getElementById('barang_id');
        if (sel.value) updateSatuan(sel);
        hitungTotal();
    });
</script>
@endpush
@endsection
