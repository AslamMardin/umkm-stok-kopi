{{-- FILE: resources/views/produksi/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Produksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-gear-fill me-2"></i>Detail Produksi
        <span class="text-muted fs-6 ms-2">{{ $produksi->no_produksi }}</span>
    </h4>
    <a href="{{ route('produksi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Informasi Produksi</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted" style="width:45%">No. Produksi</th><td>{{ $produksi->no_produksi }}</td></tr>
                    <tr><th class="text-muted">Tanggal</th><td>{{ $produksi->tanggal_produksi->format('d F Y') }}</td></tr>
                    <tr><th class="text-muted">Jenis Proses</th>
                        <td><span class="badge bg-info text-dark">{{ str_replace('_', ' + ', ucfirst($produksi->jenis_proses)) }}</span></td>
                    </tr>
                    <tr><th class="text-muted">Status</th>
                        <td>
                            <span class="badge bg-{{ $produksi->status === 'selesai' ? 'success' : ($produksi->status === 'dibatalkan' ? 'danger' : 'warning text-dark') }}">
                                {{ ucfirst($produksi->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr><th class="text-muted">Dicatat Oleh</th><td>{{ $produksi->user->name }}</td></tr>
                    <tr><th class="text-muted">Catatan</th><td>{{ $produksi->catatan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        @if($produksi->status === 'proses')
        <div class="card shadow-sm mt-3 border-warning">
            <div class="card-header fw-bold text-warning">
                <i class="bi bi-lightning-fill me-1"></i>Selesaikan Produksi
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Klik tombol ini setelah proses selesai untuk <strong>memperbarui stok secara otomatis</strong>:
                    stok bahan baku akan <span class="text-danger">berkurang</span> dan
                    stok produk jadi akan <span class="text-success">bertambah</span>.
                </p>
                <form action="{{ route('produksi.selesaikan', $produksi) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"
                        onclick="return confirm('Selesaikan produksi? Stok akan diperbarui secara otomatis.')">
                        <i class="bi bi-check-circle me-2"></i>Selesaikan & Update Stok
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($produksi->status === 'selesai')
        <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            Produksi selesai. Stok bahan baku dan produk jadi telah diperbarui otomatis.
        </div>
        @endif
    </div>

    <div class="col-md-8">
        {{-- Bahan Baku Digunakan --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold text-danger">
                <i class="bi bi-arrow-down-circle me-2"></i>Bahan Baku Digunakan (Stok Berkurang)
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bahan Baku</th>
                            <th class="text-end">Jumlah Digunakan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $adaBahan = $produksi->detailProduksis->filter(fn($d) => $d->bahan_baku_id) @endphp
                        @forelse($adaBahan as $detail)
                        <tr>
                            <td>{{ $detail->bahanBaku->nama_bahan }}</td>
                            <td class="text-end text-danger fw-semibold">
                                {{ number_format($detail->jumlah_bahan_digunakan, 2) }} {{ $detail->bahanBaku->satuan }}
                            </td>
                            <td class="text-muted small">{{ $detail->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Tidak ada bahan baku dicatat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Produk Dihasilkan --}}
        <div class="card shadow-sm">
            <div class="card-header fw-bold text-success">
                <i class="bi bi-arrow-up-circle me-2"></i>Produk Dihasilkan (Stok Bertambah)
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kemasan</th>
                            <th class="text-end">Jumlah Dihasilkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $adaProduk = $produksi->detailProduksis->filter(fn($d) => $d->produk_id) @endphp
                        @forelse($adaProduk as $detail)
                        <tr>
                            <td>{{ $detail->produkKopi->nama_produk }}</td>
                            <td>{{ $detail->produkKopi->kemasan }}</td>
                            <td class="text-end text-success fw-semibold">
                                {{ number_format($detail->jumlah_produk_dihasilkan) }} pcs
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Tidak ada produk dihasilkan dicatat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
