@extends('layouts.app')
@section('title', 'Laporan Produksi')
@section('page-title', 'Laporan Produksi')

@section('content')

<div class="d-flex gap-2 mb-3 justify-between align-center flex-wrap">
    <form action="{{ route('laporan.produksi') }}" method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
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

<div style="background:var(--cream);border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <div style="font-size:13px;color:var(--caramel);">
        Periode: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} – {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
    </div>
    <div>
        <span class="badge badge-info">{{ $data->count() }} batch produksi</span>
    </div>
</div>

<div class="card">
    <div class="card-header"><div class="card-title">Log Produksi</div></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Bahan Mentah</th>
                    <th style="text-align:right;">Qty Digunakan</th>
                    <th>→</th>
                    <th>Produk Jadi</th>
                    <th style="text-align:right;">Qty Dihasilkan</th>
                    <th style="text-align:right;">Rasio</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $p)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>{{ $p->tanggal->format('d M Y') }}</td>
                    <td>{{ $p->bahanMentah->name ?? '-' }}</td>
                    <td style="text-align:right;color:var(--danger);font-weight:600;">
                        {{ number_format($p->qty_bahan_mentah) }} {{ $p->bahanMentah->satuan ?? '' }}
                    </td>
                    <td style="color:var(--caramel);">⟹</td>
                    <td>{{ $p->produkJadi->name ?? '-' }}</td>
                    <td style="text-align:right;color:var(--success);font-weight:600;">
                        {{ number_format($p->qty_produk_jadi) }} {{ $p->produkJadi->satuan ?? '' }}
                    </td>
                    <td style="text-align:right;" class="text-muted">{{ $p->rasio_konversi }}x</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:24px;color:var(--latte);">
                        Tidak ada data produksi pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
