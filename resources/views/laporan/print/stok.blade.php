@extends('layouts.print')
@section('title', 'Cetak Laporan Stok')
@section('doc-title', 'Laporan Stok Barang')
@section('doc-subtitle',
    'Filter: ' . (request('type') == 'bahan_mentah' ? 'Bahan Mentah' : (request('type') == 'produk_jadi' ? 'Produk Jadi' : 'Semua Tipe'))
    . ' · Per ' . now()->locale('id')->isoFormat('D MMMM Y, HH:mm')
)

@section('content')

{{-- Summary Cards --}}
<div class="summary-grid cols-3">
    <div class="summary-card">
        <div class="label">Total Barang</div>
        <div class="value">{{ $barangs->count() }}</div>
    </div>
    <div class="summary-card warning">
        <div class="label">Stok Menipis (≤10)</div>
        <div class="value">{{ $barangs->where('stock', '<=', 10)->where('stock', '>', 0)->count() }}</div>
    </div>
    <div class="summary-card danger">
        <div class="label">Stok Kosong</div>
        <div class="value">{{ $barangs->where('stock', 0)->count() }}</div>
    </div>
</div>

{{-- Tabel --}}
<div class="section-title">Rincian Status Stok Barang</div>
<table>
    <thead>
        <tr>
            <th style="width:32px;">#</th>
            <th>Nama Barang</th>
            <th>Tipe</th>
            <th>Satuan</th>
            <th class="r">Stok</th>
            <th class="c">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barangs as $i => $b)
        <tr>
            <td style="color:#999;">{{ $i + 1 }}</td>
            <td style="font-weight:500;">{{ $b->name }}</td>
            <td>
                @if($b->type === 'bahan_mentah')
                    <span class="badge badge-bahan">Bahan Mentah</span>
                @else
                    <span class="badge badge-produk">Produk Jadi</span>
                @endif
            </td>
            <td>{{ $b->satuan }}</td>
            <td class="r" style="font-weight:600;">{{ number_format($b->stock) }}</td>
            <td class="c">
                @if($b->stock == 0)
                    <span class="badge badge-danger">Habis</span>
                @elseif($b->stock <= 10)
                    <span class="badge badge-warning">Menipis</span>
                @else
                    <span class="badge badge-success">Aman</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;padding:16px;color:#999;">
                Tidak ada data barang.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Area Tanda Tangan --}}
<div class="sign-area">
    <div class="sign-box">
        <div class="sign-label">Mengetahui,</div>
        <div class="sign-line">( _________________________ )</div>
    </div>
</div>

@endsection
