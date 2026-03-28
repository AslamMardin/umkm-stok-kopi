{{-- FILE: resources/views/penjualan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Catat Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-bag-plus me-2"></i>Catat Penjualan Produk</h4>
    <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('penjualan.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Informasi Penjualan</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Pelanggan <small class="text-muted">(opsional)</small></label>
                        <select name="pelanggan_id" class="form-select">
                            <option value="">-- Pelanggan Umum / Walk-in --</option>
                            @foreach($pelanggans as $pl)
                                <option value="{{ $pl->id }}" {{ old('pelanggan_id') == $pl->id ? 'selected' : '' }}>
                                    {{ $pl->nama_pelanggan }} ({{ ucfirst($pl->jenis_pelanggan) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Penjualan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_jual" class="form-control"
                               value="{{ old('tanggal_jual', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode_bayar" class="form-select" required>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="cod">COD</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diskon Global (Rp)</label>
                        <input type="number" name="diskon" id="diskon-global" class="form-control"
                               min="0" step="1000" value="{{ old('diskon', 0) }}" placeholder="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Item Produk</span>
                    <button type="button" class="btn btn-success btn-sm" id="btn-tambah-item">
                        <i class="bi bi-plus-lg"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:35%">Produk</th>
                                    <th style="width:15%">Qty</th>
                                    <th style="width:25%">Harga Satuan</th>
                                    <th style="width:20%">Subtotal</th>
                                    <th style="width:5%"></th>
                                </tr>
                            </thead>
                            <tbody id="item-rows">
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][produk_id]" class="form-select form-select-sm produk-select" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($produkList as $pk)
                                                <option value="{{ $pk->id }}" data-harga="{{ $pk->harga_jual }}" data-stok="{{ $pk->stok }}">
                                                    {{ $pk->nama_produk }} (stok: {{ $pk->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][jumlah]" class="form-control form-control-sm jumlah-input"
                                               min="1" placeholder="0" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][harga_satuan]" class="form-control form-control-sm harga-input"
                                               min="0" step="1000" placeholder="0" required>
                                    </td>
                                    <td class="subtotal-cell fw-semibold">Rp 0</td>
                                    <td class="text-center">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Harga</span>
                        <span id="display-total-harga" class="fw-semibold">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Diskon</span>
                        <span id="display-diskon" class="text-danger">- Rp 0</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-6">Total Bayar</span>
                        <span id="display-total-bayar" class="fw-bold fs-5 text-success">Rp 0</span>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-save me-2"></i>Simpan Penjualan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let rowIndex = 1;
const produkOptions = `<option value="">-- Pilih Produk --</option>` +
    `{!! collect($produkList)->map(fn($pk) =>
        "<option value='{$pk->id}' data-harga='{$pk->harga_jual}' data-stok='{$pk->stok}'>{$pk->nama_produk} (stok: {$pk->stok})</option>"
    )->implode('') !!}`;

document.getElementById('btn-tambah-item').addEventListener('click', function () {
    const tbody = document.getElementById('item-rows');
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td><select name="items[${rowIndex}][produk_id]" class="form-select form-select-sm produk-select" required>${produkOptions}</select></td>
        <td><input type="number" name="items[${rowIndex}][jumlah]" class="form-control form-control-sm jumlah-input" min="1" required></td>
        <td><input type="number" name="items[${rowIndex}][harga_satuan]" class="form-control form-control-sm harga-input" min="0" step="1000" required></td>
        <td class="subtotal-cell fw-semibold">Rp 0</td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove(); hitungGrandTotal()"><i class="bi bi-trash"></i></button></td>
    `;
    tbody.appendChild(row);
    rowIndex++;
    attachEvents(row);
});

function attachEvents(row) {
    row.querySelector('.produk-select').addEventListener('change', function () {
        const opt = this.selectedOptions[0];
        if (opt && opt.dataset.harga) {
            row.querySelector('.harga-input').value = opt.dataset.harga;
        }
        hitungSubtotal(row);
    });
    row.querySelector('.jumlah-input').addEventListener('input', () => hitungSubtotal(row));
    row.querySelector('.harga-input').addEventListener('input', () => hitungSubtotal(row));
}

function hitungSubtotal(row) {
    const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
    const harga  = parseFloat(row.querySelector('.harga-input').value) || 0;
    const sub    = jumlah * harga;
    row.querySelector('.subtotal-cell').textContent = 'Rp ' + sub.toLocaleString('id-ID');
    hitungGrandTotal();
}

function hitungGrandTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal-cell').forEach(cell => {
        const val = parseFloat(cell.textContent.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        total += val;
    });
    const diskon    = parseFloat(document.getElementById('diskon-global').value) || 0;
    const totalBayar = total - diskon;

    document.getElementById('display-total-harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('display-diskon').textContent      = '- Rp ' + diskon.toLocaleString('id-ID');
    document.getElementById('display-total-bayar').textContent = 'Rp ' + totalBayar.toLocaleString('id-ID');
}

document.getElementById('diskon-global').addEventListener('input', hitungGrandTotal);
document.querySelectorAll('.item-row').forEach(row => attachEvents(row));
</script>
@endpush
