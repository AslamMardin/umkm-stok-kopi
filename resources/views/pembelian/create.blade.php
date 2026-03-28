{{-- FILE: resources/views/pembelian/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Catat Pembelian Bahan Baku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-cart-plus me-2"></i>Catat Pembelian Bahan Baku</h4>
    <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('pembelian.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        {{-- Informasi Pembelian --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Informasi Pembelian</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Beli <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_beli" class="form-control @error('tanggal_beli') is-invalid @enderror"
                               value="{{ old('tanggal_beli', date('Y-m-d')) }}" required>
                        @error('tanggal_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Harga --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Ringkasan</div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <div class="text-muted small mb-1">Total Harga</div>
                    <div class="fw-bold fs-3 text-success" id="grand-total">Rp 0</div>
                    <div class="mt-4 w-100">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i>Simpan Pembelian
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Item Bahan Baku --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Item Bahan Baku</span>
                    <button type="button" class="btn btn-success btn-sm" id="btn-tambah-item">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="tabel-items">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:35%">Bahan Baku</th>
                                    <th style="width:20%">Jumlah (kg)</th>
                                    <th style="width:25%">Harga Satuan (Rp)</th>
                                    <th style="width:15%">Subtotal</th>
                                    <th style="width:5%"></th>
                                </tr>
                            </thead>
                            <tbody id="item-rows">
                                {{-- Row pertama, tidak bisa dihapus --}}
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][bahan_baku_id]" class="form-select form-select-sm bahan-select" required>
                                            <option value="">-- Pilih --</option>
                                            @foreach($bahanBakus as $bb)
                                                <option value="{{ $bb->id }}" data-harga="{{ $bb->harga_beli }}">
                                                    {{ $bb->nama_bahan }} (stok: {{ $bb->stok }} {{ $bb->satuan }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][jumlah]" class="form-control form-control-sm jumlah-input"
                                               step="0.01" min="0.01" placeholder="0.00" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][harga_satuan]" class="form-control form-control-sm harga-input"
                                               step="1" min="0" placeholder="0" required>
                                    </td>
                                    <td class="subtotal-cell fw-semibold">Rp 0</td>
                                    <td class="text-center">-</td>
                                </tr>
                            </tbody>
                        </table>
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
const bahanOptions = `{!! collect($bahanBakus)->map(fn($bb) =>
    "<option value='{$bb->id}' data-harga='{$bb->harga_beli}'>{$bb->nama_bahan} (stok: {$bb->stok} {$bb->satuan})</option>"
)->implode('') !!}`;

document.getElementById('btn-tambah-item').addEventListener('click', function () {
    const tbody = document.getElementById('item-rows');
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td>
            <select name="items[${rowIndex}][bahan_baku_id]" class="form-select form-select-sm bahan-select" required>
                <option value="">-- Pilih --</option>
                ${bahanOptions}
            </select>
        </td>
        <td><input type="number" name="items[${rowIndex}][jumlah]" class="form-control form-control-sm jumlah-input" step="0.01" min="0.01" required></td>
        <td><input type="number" name="items[${rowIndex}][harga_satuan]" class="form-control form-control-sm harga-input" step="1" min="0" required></td>
        <td class="subtotal-cell fw-semibold">Rp 0</td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-hapus"><i class="bi bi-trash"></i></button></td>
    `;
    tbody.appendChild(row);
    rowIndex++;
    attachEvents(row);
});

function attachEvents(row) {
    const bahanSelect = row.querySelector('.bahan-select');
    const hargaInput  = row.querySelector('.harga-input');

    bahanSelect.addEventListener('change', function () {
        const opt = this.selectedOptions[0];
        if (opt && opt.dataset.harga) {
            hargaInput.value = opt.dataset.harga;
        }
        hitungSubtotal(row);
    });

    row.querySelector('.jumlah-input').addEventListener('input', () => hitungSubtotal(row));
    hargaInput.addEventListener('input', () => hitungSubtotal(row));

    const hapusBtn = row.querySelector('.btn-hapus');
    if (hapusBtn) {
        hapusBtn.addEventListener('click', function () {
            row.remove();
            hitungGrandTotal();
        });
    }
}

function hitungSubtotal(row) {
    const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
    const harga  = parseFloat(row.querySelector('.harga-input').value) || 0;
    const sub    = jumlah * harga;
    row.querySelector('.subtotal-cell').textContent = 'Rp ' + sub.toLocaleString('id-ID');
    hitungGrandTotal();
}

function hitungGrandTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal-cell').forEach(cell => {
        const val = cell.textContent.replace('Rp ', '').replace(/\./g, '').replace(',', '.');
        total += parseFloat(val) || 0;
    });
    document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Pasang event ke row pertama
document.querySelectorAll('.item-row').forEach(row => attachEvents(row));
</script>
@endpush
