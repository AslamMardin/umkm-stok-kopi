@extends('layouts.app')
@section('title', 'Detail Penjualan #' . $penjualan->id)
@section('page-title', 'Detail Penjualan')

@section('content')
<div style="max-width:580px;">
    <div class="d-flex gap-2 mb-3 justify-between align-center">
        <span class="badge badge-success">Transaksi #{{ $penjualan->id }}</span>
        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">💰 Penjualan #{{ $penjualan->id }}</div>
            <span style="font-size:13px;color:var(--caramel);">{{ $penjualan->tanggal->format('d M Y') }}</span>
        </div>
        <div class="card-body">
            <table style="width:100%;font-size:14px;">
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;width:150px;">Produk</td>
                    <td style="font-weight:500;">{{ $penjualan->barang->name }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Pembeli</td>
                    <td>{{ $penjualan->pembeli ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Jumlah</td>
                    <td style="font-weight:600;">{{ number_format($penjualan->qty) }} {{ $penjualan->barang->satuan }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Harga Satuan</td>
                    <td>Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Total</td>
                    <td style="font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--success);">
                        Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;vertical-align:top;">Keterangan</td>
                    <td>{{ $penjualan->keterangan ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Dicatat</td>
                    <td class="text-muted">{{ $penjualan->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
