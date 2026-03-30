@extends('layouts.app')
@section('title', $supplier->name)
@section('page-title', 'Detail Supplier')

@section('content')

<div class="d-flex gap-2 mb-3 align-center justify-between flex-wrap">
    <div></div>
    <div class="d-flex gap-2">
        <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
        <a href="{{ route('supplier.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;margin-bottom:20px;align-items:start;">
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <div style="font-size:48px;margin-bottom:12px;">🤝</div>
            <div style="font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:var(--roast);margin-bottom:4px;">
                {{ $supplier->name }}
            </div>
            <div style="font-size:13px;color:var(--caramel);">
                Rp {{ number_format($totalPembelian, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:var(--latte);">Total nilai pembelian</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">Informasi Kontak</div></div>
        <div class="card-body">
            <table style="font-size:14px;">
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;width:120px;">Nama</td>
                    <td>{{ $supplier->name }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;">Telepon</td>
                    <td>{{ $supplier->phone ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;">Email</td>
                    <td>{{ $supplier->email ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="color:var(--caramel);padding:7px 24px 7px 0;vertical-align:top;">Alamat</td>
                    <td>{{ $supplier->address ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

{{-- Riwayat Pembelian --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">🛒 Riwayat Pembelian</div>
        <span class="badge badge-info">{{ $supplier->pembelians->count() }} transaksi</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th style="text-align:right;">Qty</th>
                    <th style="text-align:right;">Harga/Satuan</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplier->pembelians as $p)
                <tr>
                    <td>{{ $p->tanggal->format('d M Y') }}</td>
                    <td>{{ $p->barang->name ?? '-' }}</td>
                    <td style="text-align:right;">{{ $p->qty }} {{ $p->barang->satuan ?? '' }}</td>
                    <td style="text-align:right;">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-weight:500;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:24px;color:var(--latte);">Belum ada riwayat pembelian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
