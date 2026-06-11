@extends('layouts.print')
@section('title', 'Cetak Laporan Penjualan')
@section('doc-title', 'Laporan Penjualan')
@section('doc-subtitle',
    'Periode: ' . \Carbon\Carbon::parse($from)->locale('id')->isoFormat('D MMMM Y') .
    ' – ' . \Carbon\Carbon::parse($to)->locale('id')->isoFormat('D MMMM Y')
)

@section('content')

{{-- Summary Cards --}}
<div class="summary-grid cols-4">
    <div class="summary-card">
        <div class="label">Total Transaksi</div>
        <div class="value">{{ $data->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total Qty Terjual</div>
        <div class="value">{{ number_format($data->sum('qty')) }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total Produk</div>
        <div class="value">{{ $rekap->count() }}</div>
    </div>
    <div class="summary-card highlight">
        <div class="label">Total Pendapatan</div>
        <div class="value" style="font-size:12pt;">Rp {{ number_format($total, 0, ',', '.') }}</div>
    </div>
</div>

{{-- Rekap per Produk --}}
@if($rekap->isNotEmpty())
<div class="section-title">Rekapitulasi per Produk</div>
<table>
    <thead>
        <tr>
            <th style="width:28px;">#</th>
            <th>Produk</th>
            <th class="r">Total Qty Terjual</th>
            <th class="r">Total Nilai</th>
            <th class="r">% Kontribusi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $i => $r)
        <tr>
            <td style="color:#999;">{{ $i + 1 }}</td>
            <td style="font-weight:500;">{{ $r['barang'] }}</td>
            <td class="r">{{ number_format($r['total_qty']) }}</td>
            <td class="r" style="font-weight:600;">Rp {{ number_format($r['total_nilai'], 0, ',', '.') }}</td>
            <td class="r">
                {{ $total > 0 ? number_format(($r['total_nilai'] / $total) * 100, 1) : 0 }}%
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Rincian Transaksi --}}
<div class="section-title">Rincian Transaksi Penjualan</div>
<table>
    <thead>
        <tr>
            <th style="width:28px;">#</th>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Pembeli</th>
            <th class="r">Qty</th>
            <th class="r">Harga/Satuan</th>
            <th class="r">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $p)
        <tr>
            <td style="color:#999;">{{ $i + 1 }}</td>
            <td>{{ $p->tanggal->format('d M Y') }}</td>
            <td>{{ $p->barang->name ?? '-' }}</td>
            <td>{{ $p->pembeli ?? '—' }}</td>
            <td class="r">{{ $p->qty }}</td>
            <td class="r">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
            <td class="r" style="font-weight:600;color:#2e7d52;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:16px;color:#999;">
                Tidak ada data penjualan pada periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
    @if($data->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="6" class="r">TOTAL PENDAPATAN</td>
            <td class="r" style="color:#2e7d52;">Rp {{ number_format($total, 0, ',', '.') }}</td>
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
