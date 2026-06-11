@extends('layouts.print')
@section('title', 'Cetak Laporan Pembelian')
@section('doc-title', 'Laporan Pembelian')
@section('doc-subtitle',
    'Periode: ' . \Carbon\Carbon::parse($from)->locale('id')->isoFormat('D MMMM Y') .
    ' – ' . \Carbon\Carbon::parse($to)->locale('id')->isoFormat('D MMMM Y')
)

@section('content')

{{-- Summary Cards --}}
<div class="summary-grid cols-3">
    <div class="summary-card">
        <div class="label">Total Transaksi</div>
        <div class="value">{{ $data->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total Item</div>
        <div class="value">{{ number_format($data->sum('qty')) }}</div>
    </div>
    <div class="summary-card highlight">
        <div class="label">Total Nilai Pembelian</div>
        <div class="value" style="font-size:13pt;">Rp {{ number_format($total, 0, ',', '.') }}</div>
    </div>
</div>

{{-- Tabel Rincian --}}
<div class="section-title">Rincian Transaksi Pembelian</div>
<table>
    <thead>
        <tr>
            <th style="width:28px;">#</th>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Barang</th>
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
            <td>{{ $p->supplier->name ?? '-' }}</td>
            <td>{{ $p->barang->name ?? '-' }}</td>
            <td class="r">{{ $p->qty }} {{ $p->barang->satuan ?? '' }}</td>
            <td class="r">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
            <td class="r" style="font-weight:600;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:16px;color:#999;">
                Tidak ada data pembelian pada periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
    @if($data->isNotEmpty())
    <tfoot>
        <tr>
            <td colspan="6" class="r">TOTAL PEMBELIAN</td>
            <td class="r" style="color:#2c1a0e;">Rp {{ number_format($total, 0, ',', '.') }}</td>
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
