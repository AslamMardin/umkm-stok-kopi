@extends('layouts.app')
@section('title', 'Penjualan')
@section('page-title', 'Transaksi Penjualan')

@section('content')

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('penjualan.index') }}" method="GET"
                style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
                <div>
                    <label class="form-label" style="margin-bottom:5px;">Dari</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:5px;">Sampai</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-secondary"> <i class="fa-solid fa-filter"></i> Cari</button>
                @if (request()->hasAny(['from', 'to']))
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">✕ Reset</a>
                @endif
                <div style="margin-left:auto;">
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">+ Catat Penjualan</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Total pendapatan ringkas --}}
    @if ($penjualans->total() > 0)
        <div
            style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:16px; margin-bottom:20px;">
            {{-- Total Keseluruhan --}}
            <div
                style="background:linear-gradient(135deg,var(--success),#2d6639);border-radius:10px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;color:white;">
                <div>
                    <div style="font-size:12px;opacity:.8;text-transform:uppercase;letter-spacing:.5px;">Total Pendapatan
                        (Semua Data)</div>
                    <div style="font-family:'Fraunces',serif;font-size:24px;font-weight:700;margin-top:3px;">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </div>
                </div>
                <div style="font-size:36px;opacity:.3;">💰</div>
            </div>
            {{-- Total Halaman Ini --}}
            <div
                style="background:linear-gradient(135deg,var(--caramel),#6d472b);border-radius:10px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;color:white;">
                <div>
                    <div style="font-size:12px;opacity:.8;text-transform:uppercase;letter-spacing:.5px;">Total Pendapatan
                        Halaman Ini (Hal. {{ $penjualans->currentPage() }})</div>
                    <div style="font-family:'Fraunces',serif;font-size:24px;font-weight:700;margin-top:3px;">
                        Rp {{ number_format($totalPendapatanHalamanIni, 0, ',', '.') }}
                    </div>
                </div>
                <div style="font-size:36px;opacity:.3;">📋</div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Penjualan</div>
            <span class="text-muted">{{ $penjualans->total() }} transaksi</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Pembeli</th>
                        <th style="text-align:right;">Qty</th>
                        <th style="text-align:right;">Harga/Satuan</th>
                        <th style="text-align:right;">Total</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $i => $p)
                        <tr>
                            <td class="text-muted">{{ $penjualans->firstItem() + $i }}</td>
                            <td>{{ $p->tanggal->format('d M Y') }}</td>
                            <td style="font-weight:500;">{{ $p->barang->name ?? '-' }}</td>
                            <td>{{ $p->pembeli ?? '—' }}</td>
                            <td style="text-align:right;">{{ $p->qty }} {{ $p->barang->satuan ?? '' }}</td>
                            <td style="text-align:right;">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:600;color:var(--success);">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td style="text-align:center;white-space:nowrap;">
                                <a href="{{ route('penjualan.show', $p) }}" class="btn btn-secondary btn-sm">👁</a>
                                <form action="{{ route('penjualan.destroy', $p) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:32px;color:var(--latte);">
                                💰 Belum ada transaksi penjualan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($penjualans->hasPages())
            <div class="card-body pagination-wrap">{{ $penjualans->links() }}</div>
        @endif
    </div>

@endsection

@push('styles')
    <style>
        .pagination-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:12px;
    padding:12px 0;
}

.pagination-info{
    font-size:14px;
    color:var(--latte);
}

.pagination-custom{
    display:flex;
    align-items:center;
    gap:8px;
    list-style:none;
    padding:0;
    margin:0;
}

.pagination-custom li{
    list-style:none;
}

.pagination-custom a,
.pagination-custom span{
    display:flex;
    align-items:center;
    justify-content:center;
    min-width:40px;
    height:40px;
    padding:0 14px;
    border:1px solid #ddd;
    border-radius:10px;
    background:#fff;
    color:#333;
    text-decoration:none;
    font-weight:500;
    transition:all .2s ease;
}

.pagination-custom a:hover{
    background:#f5f5f5;
    border-color:#bbb;
}

/* HALAMAN AKTIF */
.pagination-custom .active span{
    background:#6d4c41; /* sesuaikan warna tema */
    border-color:#6d4c41;
    color:#fff !important;
    font-weight:700;
    box-shadow:0 2px 8px rgba(0,0,0,.15);
}

/* TITIK TITIK (...) */
.pagination-custom .disabled span{
    background:#f8f8f8;
    color:#888;
    border-color:#e5e5e5;
}

/* Mobile */
@media(max-width:768px){
    .pagination-container{
        flex-direction:column;
        align-items:center;
        text-align:center;
    }
}
    </style>
