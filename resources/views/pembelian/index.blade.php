{{-- ============================================================= --}}
{{-- FILE: resources/views/pembelian/index.blade.php --}}
{{-- ============================================================= --}}
@extends('layouts.app')
@section('title', 'Pembelian Bahan Baku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-cart3 me-2"></i>Pembelian Bahan Baku</h4>
    <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Catat Pembelian
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No. Pembelian</th>
                    <th>Supplier</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelians as $p)
                <tr>
                    <td class="fw-semibold">{{ $p->no_pembelian }}</td>
                    <td>{{ $p->supplier->nama_supplier }}</td>
                    <td>{{ $p->tanggal_beli->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status === 'diterima' ? 'success' : ($p->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('pembelian.show', $p) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $pembelians->links() }}</div>
</div>
@endsection
