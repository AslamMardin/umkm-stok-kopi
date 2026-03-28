{{-- ============================================================= --}}
{{-- FILE: resources/views/produksi/index.blade.php --}}
{{-- ============================================================= --}}
@extends('layouts.app')
@section('title', 'Produksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-gear-wide-connected me-2"></i>Produksi (Roasting & Packing)</h4>
    <a href="{{ route('produksi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Catat Produksi
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No. Produksi</th>
                    <th>Tanggal</th>
                    <th>Jenis Proses</th>
                    <th>Dicatat Oleh</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksis as $p)
                <tr>
                    <td class="fw-semibold">{{ $p->no_produksi }}</td>
                    <td>{{ $p->tanggal_produksi->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ str_replace('_', ' + ', ucfirst($p->jenis_proses)) }}
                        </span>
                    </td>
                    <td>{{ $p->user->name }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status === 'selesai' ? 'success' : ($p->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('produksi.show', $p) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data produksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $produksis->links() }}</div>
</div>
@endsection
