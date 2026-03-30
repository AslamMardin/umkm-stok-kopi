@extends('layouts.app')
@section('title', 'Detail Pembelian #' . $pembelian->id)
@section('page-title', 'Detail Pembelian')

@section('content')
<div style="max-width:600px;">
    <div class="d-flex gap-2 mb-3 justify-between align-center">
        <div></div>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Pembelian #{{ $pembelian->id }}</div>
            <span class="badge badge-info">{{ $pembelian->tanggal->format('d M Y') }}</span>
        </div>
        <div class="card-body">
            <table style="width:100%;font-size:14px;">
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;width:150px;">Supplier</td>
                    <td style="font-weight:500;">{{ $pembelian->supplier->name }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Barang</td>
                    <td>
                        {{ $pembelian->barang->name }}
                        <span class="badge badge-warning" style="margin-left:6px;">{{ $pembelian->barang->type_label }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Jumlah</td>
                    <td style="font-weight:600;">{{ number_format($pembelian->qty) }} {{ $pembelian->barang->satuan }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Harga Satuan</td>
                    <td>Rp {{ number_format($pembelian->harga_satuan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Total</td>
                    <td style="font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:var(--roast);">
                        Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;vertical-align:top;">Keterangan</td>
                    <td>{{ $pembelian->keterangan ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:8px 24px 8px 0;">Dicatat</td>
                    <td class="text-muted">{{ $pembelian->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
