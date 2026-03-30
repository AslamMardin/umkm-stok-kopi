@extends('layouts.app')

@section('title', $barang->name)
@section('page-title', 'Detail Barang')

@section('content')

<div class="d-flex gap-2 mb-3 align-center justify-between flex-wrap">
    <div>
        <span class="badge badge-{{ $barang->type_badge }}" style="font-size:13px;padding:5px 14px;">
            {{ $barang->type_label }}
        </span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
</div>

{{-- Info Card --}}
<div style="display:grid;grid-template-columns:auto 1fr;gap:20px;align-items:start;margin-bottom:20px;">
    <div class="card" style="min-width:200px;">
        <div class="card-body" style="text-align:center;">
            <div style="font-size:48px;margin-bottom:8px;">
                {{ $barang->type == 'bahan_mentah' ? '🌱' : '📦' }}
            </div>
            <div style="font-family:'Fraunces',serif;font-size:36px;font-weight:700;color:var(--roast);">
                {{ number_format($barang->stock) }}
            </div>
            <div style="font-size:14px;color:var(--caramel);">{{ $barang->satuan }}</div>
            <div style="font-size:12px;color:var(--latte);margin-top:4px;">Stok Saat Ini</div>
            @if($barang->stock <= 10)
                <div style="margin-top:10px;" class="badge badge-danger">⚠️ Stok Menipis</div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">Informasi Barang</div></div>
        <div class="card-body">
            <table style="font-size:14px;">
                <tr>
                    <td style="color:var(--caramel);padding:6px 20px 6px 0;width:150px;">Nama</td>
                    <td style="font-weight:500;">{{ $barang->name }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:6px 20px 6px 0;">Tipe</td>
                    <td><span class="badge badge-{{ $barang->type_badge }}">{{ $barang->type_label }}</span></td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:6px 20px 6px 0;">Satuan</td>
                    <td>{{ $barang->satuan }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:6px 20px 6px 0;">Stok</td>
                    <td style="font-weight:600;">{{ number_format($barang->stock) }} {{ $barang->satuan }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:6px 20px 6px 0;">Dibuat</td>
                    <td>{{ $barang->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:6px 20px 6px 0;">Diperbarui</td>
                    <td>{{ $barang->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

{{-- Riwayat Pembelian --}}
@if($barang->pembelians->isNotEmpty())
<div class="card mb-4">
    <div class="card-header">
        <div class="card-title">🛒 Riwayat Pembelian</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th style="text-align:right;">Qty</th>
                    <th style="text-align:right;">Harga/Satuan</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barang->pembelians as $p)
                <tr>
                    <td>{{ $p->tanggal->format('d M Y') }}</td>
                    <td>{{ $p->supplier->name ?? '-' }}</td>
                    <td style="text-align:right;">{{ $p->qty }} {{ $barang->satuan }}</td>
                    <td style="text-align:right;">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-weight:500;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Riwayat Penjualan --}}
@if($barang->penjualans->isNotEmpty())
<div class="card mb-4">
    <div class="card-header">
        <div class="card-title">💰 Riwayat Penjualan</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pembeli</th>
                    <th style="text-align:right;">Qty</th>
                    <th style="text-align:right;">Harga/Satuan</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barang->penjualans as $p)
                <tr>
                    <td>{{ $p->tanggal->format('d M Y') }}</td>
                    <td>{{ $p->pembeli ?? '—' }}</td>
                    <td style="text-align:right;">{{ $p->qty }}</td>
                    <td style="text-align:right;">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-weight:500;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
