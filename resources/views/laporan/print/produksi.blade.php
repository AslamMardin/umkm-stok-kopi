@extends('layouts.print')
@section('title', 'Cetak Laporan Produksi')
@section('doc-title', 'Laporan Produksi')
@section('doc-subtitle',
    'Periode: ' . \Carbon\Carbon::parse($from)->locale('id')->isoFormat('D MMMM Y') .
    ' – ' . \Carbon\Carbon::parse($to)->locale('id')->isoFormat('D MMMM Y')
)

@section('content')

{{-- Summary Cards --}}
<div class="summary-grid cols-3">
    <div class="summary-card">
        <div class="label">Total Batch Produksi</div>
        <div class="value">{{ $data->count() }}</div>
    </div>
    <div class="summary-card danger">
        <div class="label">Total Bahan Digunakan</div>
        <div class="value">{{ number_format($data->sum('qty_bahan_mentah')) }}</div>
    </div>
    <div class="summary-card highlight">
        <div class="label">Total Produk Dihasilkan</div>
        <div class="value">{{ number_format($data->sum('qty_produk_jadi')) }}</div>
    </div>
</div>

{{-- Tabel Rincian --}}
<div class="section-title">Log Batch Produksi</div>
<table>
    <thead>
        <tr>
            <th style="width:28px;">#</th>
            <th>Tanggal</th>
            <th>Bahan Mentah</th>
            <th class="r">Qty Digunakan</th>
            <th class="c">→</th>
            <th>Produk Jadi</th>
            <th class="r">Qty Dihasilkan</th>
            <th class="r">Rasio</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $p)
        <tr>
            <td style="color:#999;">{{ $i + 1 }}</td>
            <td>{{ $p->tanggal->format('d M Y') }}</td>
            <td>{{ $p->bahanMentah->name ?? '-' }}</td>
            <td class="r" style="font-weight:600;color:#b91c1c;">
                {{ number_format($p->qty_bahan_mentah) }} {{ $p->bahanMentah->satuan ?? '' }}
            </td>
            <td class="c" style="color:#a0522d;">⟹</td>
            <td>{{ $p->produkJadi->name ?? '-' }}</td>
            <td class="r" style="font-weight:600;color:#2e7d52;">
                {{ number_format($p->qty_produk_jadi) }} {{ $p->produkJadi->satuan ?? '' }}
            </td>
            <td class="r" style="color:#666;">{{ $p->rasio_konversi }}x</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center;padding:16px;color:#999;">
                Tidak ada data produksi pada periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
    @if($data->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="3" class="r">TOTAL</td>
            <td class="r" style="color:#b91c1c;">
                {{ number_format($data->sum('qty_bahan_mentah')) }}
            </td>
            <td></td>
            <td></td>
            <td class="r" style="color:#2e7d52;">
                {{ number_format($data->sum('qty_produk_jadi')) }}
            </td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

{{-- Area Tanda Tangan --}}
<div class="sign-area">
    <div class="sign-box">
        <div class="sign-label">Mengetahui,</div>
        <div class="sign-line">( _________________________ )</div>
    </div>
</div>

@endsection
