{{-- FILE: resources/views/produksi/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Catat Produksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Catat Produksi Baru</h4>
    <a href="{{ route('produksi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('produksi.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Informasi Produksi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Produksi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_produksi" class="form-control"
                               value="{{ old('tanggal_produksi', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Proses <span class="text-danger">*</span></label>
                        <select name="jenis_proses" class="form-select" required>
                            <option value="roasting" {{ old('jenis_proses') == 'roasting' ? 'selected' : '' }}>Roasting</option>
                            <option value="packing" {{ old('jenis_proses') == 'packing' ? 'selected' : '' }}>Packing</option>
                            <option value="roasting_packing" {{ old('jenis_proses') == 'roasting_packing' ? 'selected' : '' }}>Roasting + Packing</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Suhu roasting, kelembaban, dll">{{ old('catatan') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-2"></i>Simpan & Mulai Produksi
                    </button>
                </div>
            </div>

            <div class="alert alert-info mt-3 small">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Cara kerja integrasi stok:</strong><br>
                Setelah produksi <strong>diselesaikan</strong>, sistem akan otomatis:
                <ul class="mb-0 mt-1">
                    <li>Mengurangi stok bahan baku sesuai pemakaian</li>
                    <li>Menambah stok produk jadi sesuai hasil produksi</li>
                </ul>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Detail Bahan Baku & Hasil Produksi</span>
                    <button type="button" class="btn btn-success btn-sm" id="btn-tambah-detail">
                        <i class="bi bi-plus-lg"></i> Tambah Baris
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Bahan Baku Digunakan</th>
                                    <th>Jumlah (kg)</th>
                                    <th>Produk Dihasilkan</th>
                                    <th>Jumlah (pcs)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="detail-rows">
                                <tr class="detail-row">
                                    <td>
                                        <select name="detail[0][bahan_baku_id]" class="form-select form-select-sm">
                                            <option value="">-- (kosongkan jika tidak ada) --</option>
                                            @foreach($bahanBakus as $bb)
                                                <option value="{{ $bb->id }}">
                                                    {{ $bb->nama_bahan }} ({{ number_format($bb->stok, 1) }} {{ $bb->satuan }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="detail[0][jumlah_bahan_digunakan]"
                                               class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                    <td>
                                        <select name="detail[0][produk_id]" class="form-select form-select-sm">
                                            <option value="">-- (kosongkan jika tidak ada) --</option>
                                            @foreach($produkList as $pk)
                                                <option value="{{ $pk->id }}">{{ $pk->nama_produk }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="detail[0][jumlah_produk_dihasilkan]"
                                               class="form-control form-control-sm" min="0" placeholder="0">
                                    </td>
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
let detailIdx = 1;

const bahanOptions = `<option value="">-- (kosongkan jika tidak ada) --</option>` +
    `{!! collect($bahanBakus)->map(fn($bb) =>
        "<option value='{$bb->id}'>{$bb->nama_bahan} (" . number_format($bb->stok, 1) . " {$bb->satuan})</option>"
    )->implode('') !!}`;

const produkOptions = `<option value="">-- (kosongkan jika tidak ada) --</option>` +
    `{!! collect($produkList)->map(fn($pk) =>
        "<option value='{$pk->id}'>{$pk->nama_produk}</option>"
    )->implode('') !!}`;

document.getElementById('btn-tambah-detail').addEventListener('click', function () {
    const tbody = document.getElementById('detail-rows');
    const row = document.createElement('tr');
    row.className = 'detail-row';
    row.innerHTML = `
        <td><select name="detail[${detailIdx}][bahan_baku_id]" class="form-select form-select-sm">${bahanOptions}</select></td>
        <td><input type="number" name="detail[${detailIdx}][jumlah_bahan_digunakan]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
        <td><select name="detail[${detailIdx}][produk_id]" class="form-select form-select-sm">${produkOptions}</select></td>
        <td><input type="number" name="detail[${detailIdx}][jumlah_produk_dihasilkan]" class="form-control form-control-sm" min="0" placeholder="0"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="bi bi-trash"></i></button></td>
    `;
    tbody.appendChild(row);
    detailIdx++;
});
</script>
@endpush
