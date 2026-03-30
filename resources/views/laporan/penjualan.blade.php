@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')

<div class="d-flex gap-2 mb-3 justify-between align-center flex-wrap">
    <form action="{{ route('laporan.penjualan') }}" method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label class="form-label" style="margin-bottom:5px;">Dari</label>
            <input type="date" name="from" value="{{ $from }}" class="form-control">
        </div>
        <div>
            <label class="form-label" style="margin-bottom:5px;">Sampai</label>
            <input type="date" name="to" value="{{ $to }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-secondary">🔍 Tampilkan</button>
    </form>
    <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

{{-- Total pendapatan --}}
<div style="background:linear-gradient(135deg,var(--success),#2d6639);border-radius:10px;padding:16px 20px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;color:white;">
    <div>
        <div style="font-size:12px;opacity:.8;">Total Pendapatan ({{ \Carbon\Carbon::parse($from)->format('d M') }} – {{ \Carbon\Carbon::parse($to)->format('d M Y') }})</div>
        <div style="font-family:'Fraunces',serif;font-size:26px;font-weight:700;margin-top:3px;">
            Rp {{ number_format($total, 0, ',', '.') }}
        </div>
    </div>
    <div style="font-size:44px;opacity:.25;">📈</div>
</div>

{{-- Rekap per produk --}}
@if($rekap->isNotEmpty())
<div class="card mb-4">
    <div class="card-header"><div class="card-title">Rekapitulasi per Produk</div></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th style="text-align:right;">Total Qty Terjual</th>
                    <th style="text-align:right;">Total Nilai</th>
                    <th style="text-align:right;">% Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $i => $r)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td style="font-weight:500;">{{ $r['barang'] }}</td>
                    <td style="text-align:right;">{{ number_format($r['total_qty']) }}</td>
                    <td style="text-align:right;font-weight:600;">Rp {{ number_format($r['total_nilai'], 0, ',', '.') }}</td>
                    <td style="text-align:right;">
                        {{ $total > 0 ? number_format(($r['total_nilai'] / $total) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Rincian --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Rincian Transaksi</div>
        <span class="text-muted">{{ $data->count() }} transaksi</span>
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
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $p)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>{{ $p->tanggal->format('d M Y') }}</td>
                    <td>{{ $p->barang->name ?? '-' }}</td>
                    <td>{{ $p->pembeli ?? '—' }}</td>
                    <td style="text-align:right;">{{ $p->qty }}</td>
                    <td style="text-align:right;">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-weight:600;color:var(--success);">
                        Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:24px;color:var(--latte);">
                        Tidak ada data penjualan pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($data->isNotEmpty())
            <tfoot>
                <tr style="background:var(--cream);">
                    <td colspan="6" style="padding:10px 14px;font-weight:700;text-align:right;">TOTAL</td>
                    <td style="padding:10px 14px;font-weight:700;text-align:right;color:var(--success);">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
