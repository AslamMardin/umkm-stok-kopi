@extends('layouts.app')
@section('title', 'Catat Penjualan')
@section('page-title', 'Catat Penjualan Baru')

@section('content')
<div style="max-width:620px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Penjualan Produk</div>
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            @if($barangs->isEmpty())
                <div class="alert alert-warning">
                    ⚠️ Tidak ada produk jadi yang tersedia. Lakukan proses
                    <a href="{{ route('produksi.create') }}" style="color:var(--caramel);">produksi</a> terlebih dahulu.
                </div>
            @else
            <form action="{{ route('penjualan.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Produk Jadi <span class="required">*</span></label>
                    <select name="barang_id" id="barang_select"
                            class="form-control {{ $errors->has('barang_id') ? 'is-invalid' : '' }}"
                            onchange="updateProdukInfo(this)">
                        <option value="">-- Pilih Produk --</option>
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
                    <label class="form-label">Nama Pembeli</label>
                    <input type="text" name="pembeli" value="{{ old('pembeli') }}"
                           class="form-control" placeholder="Nama pelanggan (opsional)">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Penjualan <span class="required">*</span></label>
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
                            <span id="satuan-label" style="color:var(--caramel);font-size:13px;white-space:nowrap;">satuan</span>
                        </div>
                        @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div id="stok-info" class="form-hint" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Harga Satuan (Rp) <span class="required">*</span></label>
                        <input type="number" name="harga_satuan" id="harga_satuan"
                               value="{{ old('harga_satuan') }}" min="0" step="500"
                               class="form-control {{ $errors->has('harga_satuan') ? 'is-invalid' : '' }}"
                               placeholder="0" oninput="hitungTotal()">
                        @error('harga_satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Total preview --}}
                <div style="background:linear-gradient(135deg,#eaf4ec,#d8eddb);border-radius:8px;padding:12px 16px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--success);font-weight:500;">Total Penjualan:</span>
                    <span id="total-preview" style="font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--success);">
                        Rp 0
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="form-control"
                              placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateProdukInfo(select) {
        const opt    = select.options[select.selectedIndex];
        const satuan = opt.dataset.satuan || 'satuan';
        const stok   = opt.dataset.stok   || 0;
        document.getElementById('satuan-label').textContent = satuan;
        const info = document.getElementById('stok-info');
        if (select.value) {
            info.style.display = 'block';
            info.textContent   = `Stok tersedia: ${parseInt(stok).toLocaleString('id-ID')} ${satuan}`;
            info.style.color   = stok <= 10 ? 'var(--danger)' : 'var(--caramel)';
        } else {
            info.style.display = 'none';
        }
        hitungTotal();
    }

    function hitungTotal() {
        const qty   = parseFloat(document.getElementById('qty').value)          || 0;
        const harga = parseFloat(document.getElementById('harga_satuan').value) || 0;
        document.getElementById('total-preview').textContent =
            'Rp ' + (qty * harga).toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sel = document.getElementById('barang_select');
        if (sel && sel.value) updateProdukInfo(sel);
        hitungTotal();
    });
</script>
@endpush
@endsection
