{{-- ============================================================= --}}
{{-- FILE: resources/views/stok/bahan-baku-detail.blade.php --}}
{{-- ============================================================= --}}
@extends('layouts.app')
@section('title', 'Riwayat Stok Bahan Baku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-clock-history me-2"></i>Riwayat Stok: {{ $bahanBaku->nama_bahan }}
        </h4>
        <small class="text-muted">
            Stok saat ini:
            <strong class="{{ $bahanBaku->isStokKritis() ? 'text-danger' : 'text-success' }}">
                {{ number_format($bahanBaku->stok, 2) }} {{ $bahanBaku->satuan }}
            </strong>
            @if($bahanBaku->isStokKritis())
                <span class="badge bg-danger ms-1">Kritis!</span>
            @endif
        </small>
    </div>
    <a href="{{ route('stok.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3">
    {{-- Riwayat Pembelian --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold text-success">
                <i class="bi bi-arrow-up-circle me-2"></i>Masuk (dari Pembelian)
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pembelian</th>
                            <th>Supplier</th>
                            <th>Tanggal</th>
                            <th class="text-end">+Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $item)
                        @if($item->pembelian->status === 'diterima')
                        <tr>
                            <td>
                                <a href="{{ route('pembelian.show', $item->pembelian) }}">
                                    {{ $item->pembelian->no_pembelian }}
                                </a>
                            </td>
                            <td class="small">{{ $item->pembelian->supplier->nama_supplier }}</td>
                            <td class="small">{{ $item->pembelian->tanggal_beli->format('d/m/Y') }}</td>
                            <td class="text-end text-success fw-semibold">
                                +{{ number_format($item->jumlah, 2) }} {{ $bahanBaku->satuan }}
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat pembelian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $pembelians->links() }}</div>
        </div>
    </div>

    {{-- Riwayat Produksi (pemakaian) --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold text-danger">
                <i class="bi bi-arrow-down-circle me-2"></i>Keluar (digunakan Produksi)
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Produksi</th>
                            <th>Proses</th>
                            <th>Tanggal</th>
                            <th class="text-end">-Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksis as $item)
                        @if($item->produksi->status === 'selesai')
                        <tr>
                            <td>
                                <a href="{{ route('produksi.show', $item->produksi) }}">
                                    {{ $item->produksi->no_produksi }}
                                </a>
                            </td>
                            <td class="small">{{ ucfirst(str_replace('_', '+', $item->produksi->jenis_proses)) }}</td>
                            <td class="small">{{ $item->produksi->tanggal_produksi->format('d/m/Y') }}</td>
                            <td class="text-end text-danger fw-semibold">
                                -{{ number_format($item->jumlah_bahan_digunakan, 2) }} {{ $bahanBaku->satuan }}
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat pemakaian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $produksis->links() }}</div>
        </div>
    </div>
</div>
@endsection


{{-- ============================================================= --}}
{{-- FILE: resources/views/stok/produk-detail.blade.php --}}
{{-- ============================================================= --}}
@extends('layouts.app')
@section('title', 'Riwayat Stok Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-clock-history me-2"></i>Riwayat Stok: {{ $produkKopi->nama_produk }}
        </h4>
        <small class="text-muted">
            Stok saat ini:
            <strong class="{{ $produkKopi->isStokKritis() ? 'text-danger' : 'text-success' }}">
                {{ $produkKopi->stok }} pcs
            </strong>
            | Kemasan: {{ $produkKopi->kemasan }}
            | Harga Jual: Rp {{ number_format($produkKopi->harga_jual, 0, ',', '.') }}
        </small>
    </div>
    <a href="{{ route('stok.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3">
    {{-- Riwayat Produksi (masuk) --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold text-success">
                <i class="bi bi-arrow-up-circle me-2"></i>Masuk (dari Produksi)
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Produksi</th>
                            <th>Tanggal</th>
                            <th class="text-end">+Jumlah (pcs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksis as $item)
                        @if($item->produksi->status === 'selesai')
                        <tr>
                            <td>
                                <a href="{{ route('produksi.show', $item->produksi) }}">
                                    {{ $item->produksi->no_produksi }}
                                </a>
                            </td>
                            <td class="small">{{ $item->produksi->tanggal_produksi->format('d/m/Y') }}</td>
                            <td class="text-end text-success fw-semibold">
                                +{{ number_format($item->jumlah_produk_dihasilkan) }} pcs
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat produksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $produksis->links() }}</div>
        </div>
    </div>

    {{-- Riwayat Penjualan (keluar) --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold text-danger">
                <i class="bi bi-arrow-down-circle me-2"></i>Keluar (dari Penjualan)
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Penjualan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th class="text-end">-Jumlah (pcs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $item)
                        @if($item->penjualan->status === 'lunas')
                        <tr>
                            <td>
                                <a href="{{ route('penjualan.show', $item->penjualan) }}">
                                    {{ $item->penjualan->no_penjualan }}
                                </a>
                            </td>
                            <td class="small">{{ $item->penjualan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                            <td class="small">{{ $item->penjualan->tanggal_jual->format('d/m/Y') }}</td>
                            <td class="text-end text-danger fw-semibold">
                                -{{ number_format($item->jumlah) }} pcs
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $penjualans->links() }}</div>
        </div>
    </div>
</div>
@endsection
