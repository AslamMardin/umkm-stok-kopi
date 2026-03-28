{{-- ============================================================= --}}
{{-- FILE: resources/views/stok/index.blade.php --}}
{{-- ============================================================= --}}
@extends('layouts.app')
@section('title', 'Monitor Stok')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-boxes me-2"></i>Monitor Stok</h4>
    <small class="text-muted">Ringkasan stok bahan baku dan produk jadi secara real-time</small>
</div>

{{-- Stok Bahan Baku --}}
<div class="card shadow-sm mb-4">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bag-fill me-2 text-warning"></i>Stok Bahan Baku</span>
        <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-plus-lg me-1"></i>Tambah Pembelian
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Bahan</th>
                    <th class="text-center">Stok Saat Ini</th>
                    <th class="text-center">Stok Minimum</th>
                    <th class="text-center">Status</th>
                    <th>Harga Beli/kg</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahanBakus as $bb)
                <tr class="{{ $bb->isStokKritis() ? 'table-danger' : '' }}">
                    <td class="fw-semibold">{{ $bb->nama_bahan }}</td>
                    <td class="text-center">
                        <span class="fw-bold {{ $bb->isStokKritis() ? 'text-danger' : 'text-success' }}">
                            {{ number_format($bb->stok, 2) }}
                        </span>
                        <span class="text-muted small">{{ $bb->satuan }}</span>
                    </td>
                    <td class="text-center text-muted">{{ number_format($bb->stok_minimum, 2) }} {{ $bb->satuan }}</td>
                    <td class="text-center">
                        @if($bb->isStokKritis())
                            <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Kritis</span>
                        @else
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aman</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($bb->harga_beli, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('stok.bahan-baku', $bb) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data bahan baku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Stok Produk Jadi --}}
<div class="card shadow-sm">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cup-hot me-2 text-success"></i>Stok Produk Jadi</span>
        <a href="{{ route('produksi.create') }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-gear me-1"></i>Tambah Produksi
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kemasan</th>
                    <th>Jenis Roast</th>
                    <th class="text-center">Stok (pcs)</th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-center">Status</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produkList as $pk)
                <tr class="{{ $pk->isStokKritis() ? 'table-warning' : '' }}">
                    <td class="text-muted small">{{ $pk->kode_produk }}</td>
                    <td class="fw-semibold">{{ $pk->nama_produk }}</td>
                    <td>{{ $pk->kemasan }}</td>
                    <td>
                        <span class="badge bg-{{ ['light'=>'info','medium'=>'warning','dark'=>'dark','extra_dark'=>'secondary'][$pk->jenis_roast] ?? 'secondary' }} text-dark">
                            {{ ucfirst(str_replace('_', ' ', $pk->jenis_roast)) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold {{ $pk->isStokKritis() ? 'text-danger' : 'text-success' }}">
                            {{ $pk->stok }}
                        </span>
                    </td>
                    <td class="text-center text-muted">{{ $pk->stok_minimum }}</td>
                    <td class="text-center">
                        @if($pk->isStokKritis())
                            <span class="badge bg-danger">Kritis</span>
                        @else
                            <span class="badge bg-success">Aman</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($pk->harga_jual, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('stok.produk', $pk) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-3">Belum ada data produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
