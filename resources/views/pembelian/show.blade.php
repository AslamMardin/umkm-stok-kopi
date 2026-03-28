{{-- FILE: resources/views/pembelian/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Pembelian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-file-earmark-text me-2"></i>Detail Pembelian
        <span class="text-muted fs-6 ms-2">{{ $pembelian->no_pembelian }}</span>
    </h4>
    <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3">
    {{-- Info Pembelian --}}
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Informasi Pembelian</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted" style="width:45%">No. Pembelian</th><td>{{ $pembelian->no_pembelian }}</td></tr>
                    <tr><th class="text-muted">Supplier</th><td>{{ $pembelian->supplier->nama_supplier }}</td></tr>
                    <tr><th class="text-muted">Tanggal Beli</th><td>{{ $pembelian->tanggal_beli->format('d F Y') }}</td></tr>
                    <tr><th class="text-muted">Dicatat Oleh</th><td>{{ $pembelian->user->name }}</td></tr>
                    <tr><th class="text-muted">Status</th>
                        <td>
                            <span class="badge bg-{{ $pembelian->status === 'diterima' ? 'success' : ($pembelian->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                                {{ ucfirst($pembelian->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr><th class="text-muted">Catatan</th><td>{{ $pembelian->catatan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Aksi --}}
        @if($pembelian->status === 'pending')
        <div class="card shadow-sm mt-3 border-warning">
            <div class="card-header fw-bold text-warning">
                <i class="bi bi-lightning-fill me-1"></i>Aksi Pembelian
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Konfirmasi penerimaan barang untuk <strong>memperbarui stok bahan baku secara otomatis</strong>.
                </p>
                <form action="{{ route('pembelian.terima', $pembelian) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 mb-2"
                        onclick="return confirm('Konfirmasi penerimaan barang? Stok bahan baku akan bertambah otomatis.')">
                        <i class="bi bi-check-circle me-2"></i>Terima Barang & Update Stok
                    </button>
                </form>
                <form action="{{ route('pembelian.batalkan', $pembelian) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100"
                        onclick="return confirm('Batalkan pembelian ini?')">
                        <i class="bi bi-x-circle me-2"></i>Batalkan Pembelian
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($pembelian->status === 'diterima')
        <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            Barang telah diterima. Stok bahan baku sudah diperbarui secara otomatis.
        </div>
        @endif
    </div>

    {{-- Detail Item --}}
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Item Bahan Baku</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bahan Baku</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembelian->detailPembelians as $detail)
                        <tr>
                            <td>{{ $detail->bahanBaku->nama_bahan }}</td>
                            <td class="text-end">{{ number_format($detail->jumlah, 2) }} {{ $detail->bahanBaku->satuan }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total Harga</td>
                            <td class="text-end fw-bold fs-6 text-success">
                                Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
