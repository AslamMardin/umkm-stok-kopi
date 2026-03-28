@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Dashboard SCM Kopi</h4>
        <small class="text-muted">Digitalisasi Supply Chain UMKM Kopi Polewali Mandar</small>
    </div>
    <small class="text-muted">{{ now()->isoFormat('dddd, D MMMM Y') }}</small>
</div>

{{-- Kartu Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stok shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Pembelian Bulan Ini</div>
                        <div class="fw-bold fs-5">Rp {{ number_format($pembelianBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <i class="bi bi-cart3 fs-2 text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stok shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Penjualan Bulan Ini</div>
                        <div class="fw-bold fs-5">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <i class="bi bi-bag-check fs-2 text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stok shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Produksi Aktif</div>
                        <div class="fw-bold fs-5">{{ $produksiAktif }} Batch</div>
                    </div>
                    <i class="bi bi-gear-wide-connected fs-2 text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm {{ ($bahanBakuKritis + $produkStokKritis) > 0 ? 'border-danger' : 'card-stok' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Stok Kritis</div>
                        <div class="fw-bold fs-5 {{ ($bahanBakuKritis + $produkStokKritis) > 0 ? 'text-danger' : '' }}">
                            {{ $bahanBakuKritis + $produkStokKritis }} Item
                        </div>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-2 {{ ($bahanBakuKritis + $produkStokKritis) > 0 ? 'text-danger' : 'text-secondary' }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Peringatan Stok Kritis --}}
    @if($bahanBakuKritisList->isNotEmpty() || $produkKritisList->isNotEmpty())
    <div class="col-md-6">
        <div class="card border-danger shadow-sm h-100">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-exclamation-triangle me-2"></i>Peringatan Stok Kritis
            </div>
            <div class="card-body p-0">
                @if($bahanBakuKritisList->isNotEmpty())
                    <div class="px-3 pt-2 pb-1 text-muted small fw-bold">Bahan Baku</div>
                    @foreach($bahanBakuKritisList as $bb)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <span>{{ $bb->nama_bahan }}</span>
                        <span class="badge bg-danger">{{ $bb->stok }} {{ $bb->satuan }}</span>
                    </div>
                    @endforeach
                @endif
                @if($produkKritisList->isNotEmpty())
                    <div class="px-3 pt-2 pb-1 text-muted small fw-bold">Produk Jadi</div>
                    @foreach($produkKritisList as $pk)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <span>{{ $pk->nama_produk }}</span>
                        <span class="badge bg-danger">{{ $pk->stok }} pcs</span>
                    </div>
                    @endforeach
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('stok.index') }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-boxes me-1"></i>Lihat Semua Stok
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Pembelian Terbaru --}}
    <div class="col-md-{{ ($bahanBakuKritisList->isNotEmpty() || $produkKritisList->isNotEmpty()) ? '6' : '12' }}">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">
                <i class="bi bi-clock-history me-2"></i>Pembelian Terbaru
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pembelian</th>
                            <th>Supplier</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelianTerbaru as $p)
                        <tr>
                            <td><a href="{{ route('pembelian.show', $p) }}">{{ $p->no_pembelian }}</a></td>
                            <td>{{ $p->supplier->nama_supplier }}</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $p->status === 'diterima' ? 'success' : ($p->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Penjualan Terbaru --}}
<div class="card shadow-sm">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bag-check me-2"></i>Penjualan Terbaru</span>
        <a href="{{ route('penjualan.create') }}" class="btn btn-sm btn-success">
            <i class="bi bi-plus-lg me-1"></i>Catat Penjualan
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Penjualan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualanTerbaru as $pj)
                <tr>
                    <td><a href="{{ route('penjualan.show', $pj) }}">{{ $pj->no_penjualan }}</a></td>
                    <td>{{ $pj->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                    <td>{{ $pj->tanggal_jual->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($pj->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $pj->status === 'lunas' ? 'success' : ($pj->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                            {{ ucfirst($pj->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
