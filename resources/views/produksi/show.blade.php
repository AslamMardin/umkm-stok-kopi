@extends('layouts.app')
@section('title', 'Detail Produksi #' . $produksi->id)
@section('page-title', 'Detail Batch Produksi')

@section('content')
<div style="max-width:620px;">
    <div class="d-flex gap-2 mb-3 justify-between align-center">
        <span class="badge badge-info">Batch #{{ $produksi->id }}</span>
        <a href="{{ route('produksi.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">⚙️ Informasi Produksi</div>
            <span style="font-size:13px;color:var(--caramel);">{{ $produksi->tanggal->format('d M Y') }}</span>
        </div>
        <div class="card-body">

            {{-- Visual flow --}}
            <div style="display:flex;gap:16px;align-items:center;justify-content:center;
                        background:var(--cream);border-radius:10px;padding:20px;margin-bottom:24px;">
                <div style="text-align:center;flex:1;">
                    <div style="font-size:32px;margin-bottom:6px;">🌱</div>
                    <div style="font-weight:600;font-size:14px;color:var(--roast);">{{ $produksi->bahanMentah->name }}</div>
                    <div style="font-size:22px;font-weight:700;color:var(--danger);margin-top:4px;">
                        −{{ number_format($produksi->qty_bahan_mentah) }}
                    </div>
                    <div style="font-size:12px;color:var(--latte);">{{ $produksi->bahanMentah->satuan }}</div>
                </div>
                <div style="font-size:32px;color:var(--caramel);">⟹</div>
                <div style="text-align:center;flex:1;">
                    <div style="font-size:32px;margin-bottom:6px;">📦</div>
                    <div style="font-weight:600;font-size:14px;color:var(--roast);">{{ $produksi->produkJadi->name }}</div>
                    <div style="font-size:22px;font-weight:700;color:var(--success);margin-top:4px;">
                        +{{ number_format($produksi->qty_produk_jadi) }}
                    </div>
                    <div style="font-size:12px;color:var(--latte);">{{ $produksi->produkJadi->satuan }}</div>
                </div>
            </div>

            <table style="width:100%;font-size:14px;">
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;width:160px;">Tanggal Produksi</td>
                    <td>{{ $produksi->tanggal->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;">Rasio Konversi</td>
                    <td>
                        1 {{ $produksi->bahanMentah->satuan }} bahan →
                        <strong>{{ $produksi->rasio_konversi }}</strong> {{ $produksi->produkJadi->satuan }} produk
                    </td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;vertical-align:top;">Keterangan</td>
                    <td>{{ $produksi->keterangan ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;">Dicatat</td>
                    <td class="text-muted">{{ $produksi->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
