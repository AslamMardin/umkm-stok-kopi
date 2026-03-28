{{-- FILE: resources/views/penjualan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-receipt me-2"></i>Detail Penjualan
        <span class="text-muted fs-6 ms-2">{{ $penjualan->no_penjualan }}</span>
    </h4>
    <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Informasi Penjualan</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted" style="width:45%">No. Penjualan</th><td>{{ $penjualan->no_penjualan }}</td></tr>
                    <tr><th class="text-muted">Pelanggan</th><td>{{ $penjualan->pelanggan->nama_pelanggan ?? 'Umum' }}</td></tr>
                    <tr><th class="text-muted">Tanggal</th><td>{{ $penjualan->tanggal_jual->format('d F Y') }}</td></tr>
                    <tr><th class="text-muted">Metode Bayar</th>
                        <td><span class="badge bg-secondary">{{ ucfirst($penjualan->metode_bayar) }}</span></td>
                    </tr>
                    <tr><th class="text-muted">Total Harga</th><td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td></tr>
                    <tr><th class="text-muted">Diskon</th><td class="text-danger">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</td></tr>
                    <tr><th class="text-muted">Total Bayar</th>
                        <td class="fw-bold text-success fs-6">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr><th class="text-muted">Status</th>
                        <td>
                            <span class="badge bg-{{ $penjualan->status === 'lunas' ? 'success' : ($penjualan->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                                {{ ucfirst($penjualan->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr><th class="text-muted">Dicatat Oleh</th><td>{{ $penjualan->user->name }}</td></tr>
                    <tr><th class="text-muted">Catatan</th><td>{{ $penjualan->catatan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Aksi --}}
        @if($penjualan->status === 'pending')
        <div class="card shadow-sm mt-3 border-warning">
            <div class="card-header fw-bold text-warning">
                <i class="bi bi-lightning-fill me-1"></i>Aksi Penjualan
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Konfirmasi pembayaran untuk <strong>mengurangi stok produk secara otomatis</strong>.
                </p>
                <form action="{{ route('penjualan.konfirmasi', $penjualan) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"
                        onclick="return confirm('Konfirmasi penjualan ini lunas? Stok produk akan berkurang.')">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Lunas & Update Stok
                    </button>
                </form>
                <form action="{{ route('penjualan.batalkan', $penjualan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100"
                        onclick="return confirm('Batalkan penjualan ini?')">
                        <i class="bi bi-x-circle me-2"></i>Batalkan Penjualan
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($penjualan->status === 'lunas')
        <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            Penjualan lunas. Stok produk telah berkurang secara otomatis.
        </div>
        <form action="{{ route('penjualan.batalkan', $penjualan) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                onclick="return confirm('Batalkan penjualan yang sudah lunas? Stok akan dikembalikan.')">
                <i class="bi bi-arrow-counterclockwise me-2"></i>Batalkan & Kembalikan Stok
            </button>
        </form>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Item Produk Terjual</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Kemasan</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualans as $detail)
                        <tr>
                            <td>{{ $detail->produkKopi->nama_produk }}</td>
                            <td>{{ $detail->produkKopi->kemasan }}</td>
                            <td class="text-end">{{ $detail->jumlah }} pcs</td>
                            <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end">Total Harga</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @if($penjualan->diskon > 0)
                        <tr>
                            <td colspan="4" class="text-end text-danger">Diskon</td>
                            <td class="text-end text-danger">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-6">Total Bayar</td>
                            <td class="text-end fw-bold fs-6 text-success">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
