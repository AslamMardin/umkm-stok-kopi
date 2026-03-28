{{-- ============================================================= --}}
{{-- FILE: resources/views/penjualan/index.blade.php --}}
{{-- ============================================================= --}}
@extends('layouts.app')
@section('title', 'Penjualan Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-bag-check me-2"></i>Penjualan Produk Kopi</h4>
    <a href="{{ route('penjualan.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Catat Penjualan
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No. Penjualan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total Bayar</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualans as $pj)
                <tr>
                    <td class="fw-semibold">{{ $pj->no_penjualan }}</td>
                    <td>{{ $pj->pelanggan->nama_pelanggan ?? '<span class="text-muted">Umum</span>' }}</td>
                    <td>{{ $pj->tanggal_jual->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($pj->total_bayar, 0, ',', '.') }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($pj->metode_bayar) }}</span></td>
                    <td>
                        <span class="badge bg-{{ $pj->status === 'lunas' ? 'success' : ($pj->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                            {{ ucfirst($pj->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('penjualan.show', $pj) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $penjualans->links() }}</div>
</div>
@endsection
