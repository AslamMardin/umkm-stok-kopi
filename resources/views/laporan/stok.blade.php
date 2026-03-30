@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok Barang')

@section('content')

<div class="d-flex gap-2 mb-3 justify-between align-center flex-wrap">
    <form action="{{ route('laporan.stok') }}" method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label class="form-label" style="margin-bottom:5px;">Filter Tipe</label>
            <select name="type" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="bahan_mentah" {{ request('type') == 'bahan_mentah' ? 'selected' : '' }}>Bahan Mentah</option>
                <option value="produk_jadi"  {{ request('type') == 'produk_jadi'  ? 'selected' : '' }}>Produk Jadi</option>
            </select>
        </div>
    </form>
    <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">← Kembali ke Laporan</a>
</div>

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;">
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--caramel);margin-bottom:4px;">Total Barang</div>
            <div style="font-family:'Fraunces',serif;font-size:28px;font-weight:700;color:var(--roast);">{{ $barangs->count() }}</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--warning);margin-bottom:4px;">Stok Menipis (≤10)</div>
            <div style="font-family:'Fraunces',serif;font-size:28px;font-weight:700;color:var(--warning);">
                {{ $barangs->where('stock', '<=', 10)->count() }}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--danger);margin-bottom:4px;">Stok Kosong</div>
            <div style="font-family:'Fraunces',serif;font-size:28px;font-weight:700;color:var(--danger);">
                {{ $barangs->where('stock', 0)->count() }}
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Status Stok Barang</div>
        <span class="text-muted">Per {{ now()->format('d M Y H:i') }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Satuan</th>
                    <th style="text-align:right;">Stok</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $i => $b)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td style="font-weight:500;">{{ $b->name }}</td>
                    <td><span class="badge badge-{{ $b->type_badge }}">{{ $b->type_label }}</span></td>
                    <td>{{ $b->satuan }}</td>
                    <td style="text-align:right;font-weight:600;">{{ number_format($b->stock) }}</td>
                    <td style="text-align:center;">
                        @if($b->stock == 0)
                            <span class="badge badge-danger">Habis</span>
                        @elseif($b->stock <= 10)
                            <span class="badge badge-warning">Menipis</span>
                        @else
                            <span class="badge badge-success">Aman</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
