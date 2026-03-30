@extends('layouts.app')
@section('title', 'Laporan Pembelian')
@section('page-title', 'Laporan Pembelian')

@section('content')

<div class="d-flex gap-2 mb-3 justify-between align-center flex-wrap">
    <form action="{{ route('laporan.pembelian') }}" method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
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

{{-- Total --}}
<div style="background:var(--cream);border-radius:10px;padding:16px 20px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <div style="font-size:13px;color:var(--caramel);">Total Nilai Pembelian ({{ \Carbon\Carbon::parse($from)->format('d M') }} – {{ \Carbon\Carbon::parse($to)->format('d M Y') }})</div>
    <div style="font-family:'Fraunces',serif;font-size:24px;font-weight:700;color:var(--roast);">
        Rp {{ number_format($total, 0, ',', '.') }}
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Rincian Pembelian</div>
        <span class="text-muted">{{ $data->count() }} transaksi</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Barang</th>
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
                    <td>{{ $p->supplier->name ?? '-' }}</td>
                    <td>{{ $p->barang->name ?? '-' }}</td>
                    <td style="text-align:right;">{{ $p->qty }} {{ $p->barang->satuan ?? '' }}</td>
                    <td style="text-align:right;">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-weight:600;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:24px;color:var(--latte);">
                        Tidak ada data pembelian pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($data->isNotEmpty())
            <tfoot>
                <tr style="background:var(--cream);">
                    <td colspan="6" style="padding:10px 14px;font-weight:700;text-align:right;">TOTAL</td>
                    <td style="padding:10px 14px;font-weight:700;text-align:right;color:var(--roast);">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
