@extends('layouts.app')
@section('title', 'Catat Produksi')
@section('page-title', 'Catat Batch Produksi')

@section('content')
<div style="max-width:700px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Produksi Baru</div>
            <a href="{{ route('produksi.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">

            {{-- Alur visual --}}
            <div style="display:flex;align-items:center;justify-content:center;gap:16px;margin-bottom:24px;padding:14px;background:var(--cream);border-radius:8px;">
                <div style="text-align:center;">
                    <div style="font-size:28px;">🌱</div>
                    <div style="font-size:11px;color:var(--caramel);margin-top:4px;">Bahan Mentah</div>
                    <div style="font-size:11px;color:var(--danger);font-weight:600;">Stok Berkurang</div>
                </div>
                <div style="font-size:28px;color:var(--caramel);">⟹</div>
                <div style="text-align:center;font-size:28px;">⚙️</div>
                <div style="font-size:28px;color:var(--caramel);">⟹</div>
                <div style="text-align:center;">
                    <div style="font-size:28px;">📦</div>
                    <div style="font-size:11px;color:var(--caramel);margin-top:4px;">Produk Jadi</div>
                    <div style="font-size:11px;color:var(--success);font-weight:600;">Stok Bertambah</div>
                </div>
            </div>

            <form action="{{ route('produksi.store') }}" method="POST">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    {{-- Bahan Mentah --}}
                    <div>
                        <div class="form-group">
                            <label class="form-label">🌱 Bahan Mentah (Input) <span class="required">*</span></label>
                            <select name="barang_bahan_mentah_id" id="bahan_mentah_select"
                                    class="form-control {{ $errors->has('barang_bahan_mentah_id') ? 'is-invalid' : '' }}"
                                    onchange="updateBahanInfo(this)">
                                <option value="">-- Pilih Bahan Mentah --</option>
                                @foreach($bahanMentahs as $b)
                                    <option value="{{ $b->id }}"
                                            data-satuan="{{ $b->satuan }}"
                                            data-stok="{{ $b->stock }}"
                                            {{ old('barang_bahan_mentah_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }} ({{ $b->stock }} {{ $b->satuan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_bahan_mentah_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Qty Bahan Digunakan <span class="required">*</span></label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="number" name="qty_bahan_mentah" id="qty_bahan"
                                       value="{{ old('qty_bahan_mentah') }}" min="1"
                                       class="form-control {{ $errors->has('qty_bahan_mentah') ? 'is-invalid' : '' }}"
                                       placeholder="0">
                                <span id="satuan-bahan" style="color:var(--caramel);font-size:13px;white-space:nowrap;">satuan</span>
                            </div>
                            @error('qty_bahan_mentah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="stok-info" class="form-hint" style="display:none;"></div>
                        </div>
                    </div>

                    {{-- Produk Jadi --}}
                    <div>
                        <div class="form-group">
                            <label class="form-label">📦 Produk Jadi (Output) <span class="required">*</span></label>
                            <select name="barang_produk_jadi_id"
                                    class="form-control {{ $errors->has('barang_produk_jadi_id') ? 'is-invalid' : '' }}"
                                    onchange="updateProdukInfo(this)">
                                <option value="">-- Pilih Produk Jadi --</option>
                                @foreach($produkJadis as $b)
                                    <option value="{{ $b->id }}"
                                            data-satuan="{{ $b->satuan }}"
                                            {{ old('barang_produk_jadi_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_produk_jadi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Qty Produk Dihasilkan <span class="required">*</span></label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="number" name="qty_produk_jadi" id="qty_produk"
                                       value="{{ old('qty_produk_jadi') }}" min="1"
                                       class="form-control {{ $errors->has('qty_produk_jadi') ? 'is-invalid' : '' }}"
                                       placeholder="0">
                                <span id="satuan-produk" style="color:var(--success);font-size:13px;white-space:nowrap;">satuan</span>
                            </div>
                            @error('qty_produk_jadi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Produksi <span class="required">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}">
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="form-control"
                              placeholder="Catatan batch produksi (opsional)">{{ old('keterangan') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary">Proses Produksi</button>
                    <a href="{{ route('produksi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateBahanInfo(select) {
        const opt   = select.options[select.selectedIndex];
        const satuan = opt.dataset.satuan || 'satuan';
        const stok   = opt.dataset.stok   || 0;
        document.getElementById('satuan-bahan').textContent = satuan;

        const info = document.getElementById('stok-info');
        if (select.value) {
            info.style.display = 'block';
            info.textContent   = `Stok tersedia: ${parseInt(stok).toLocaleString('id-ID')} ${satuan}`;
            info.style.color   = stok <= 10 ? 'var(--danger)' : 'var(--caramel)';
        } else {
            info.style.display = 'none';
        }
    }

    function updateProdukInfo(select) {
        const opt = select.options[select.selectedIndex];
        document.getElementById('satuan-produk').textContent = opt.dataset.satuan || 'satuan';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const bahanSel   = document.getElementById('bahan_mentah_select');
        const produkSels = document.querySelectorAll('[name="barang_produk_jadi_id"]');
        if (bahanSel.value)   updateBahanInfo(bahanSel);
        produkSels.forEach(s => { if (s.value) updateProdukInfo(s); });
    });
</script>
@endpush
@endsection
