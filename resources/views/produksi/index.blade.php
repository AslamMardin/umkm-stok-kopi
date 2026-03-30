@extends('layouts.app')
@section('title', 'Produksi')
@section('page-title', 'Manajemen Produksi')

@section('content')

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('produksi.index') }}" method="GET"
              style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
            <div>
                <label class="form-label" style="margin-bottom:5px;">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label" style="margin-bottom:5px;">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary">🔍 Filter</button>
            @if(request()->hasAny(['from','to']))
                <a href="{{ route('produksi.index') }}" class="btn btn-secondary">✕ Reset</a>
            @endif
            <div style="margin-left:auto;">
                <a href="{{ route('produksi.create') }}" class="btn btn-primary">⚙️ Catat Produksi</a>
            </div>
        </form>
    </div>
</div>

{{-- Penjelasan alur --}}
<div style="background:linear-gradient(135deg,var(--cream),#efe8d5);border-radius:10px;padding:14px 18px;margin-bottom:20px;border-left:4px solid var(--caramel);font-size:13px;color:var(--roast);">
    💡 <strong>Cara Kerja Produksi:</strong>
    Saat produksi dicatat, stok <em>bahan mentah</em> akan otomatis <strong>dikurangi</strong>
    dan stok <em>produk jadi</em> akan otomatis <strong>bertambah</strong>.
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Log Batch Produksi</div>
        <span class="text-muted">{{ $produksis->total() }} batch</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Bahan Mentah (Input)</th>
                    <th style="text-align:right;">Qty Digunakan</th>
                    <th style="text-align:center;">→</th>
                    <th>Produk Jadi (Output)</th>
                    <th style="text-align:right;">Qty Dihasilkan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksis as $i => $p)
                <tr>
                    <td class="text-muted">{{ $produksis->firstItem() + $i }}</td>
                    <td>{{ $p->tanggal->format('d M Y') }}</td>
                    <td>
                        <span style="font-weight:500;">{{ $p->bahanMentah->name ?? '-' }}</span>
                    </td>
                    <td style="text-align:right;color:var(--danger);font-weight:600;">
                        −{{ number_format($p->qty_bahan_mentah) }} {{ $p->bahanMentah->satuan ?? '' }}
                    </td>
                    <td style="text-align:center;color:var(--caramel);">⟹</td>
                    <td>
                        <span style="font-weight:500;">{{ $p->produkJadi->name ?? '-' }}</span>
                    </td>
                    <td style="text-align:right;color:var(--success);font-weight:600;">
                        +{{ number_format($p->qty_produk_jadi) }} {{ $p->produkJadi->satuan ?? '' }}
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('produksi.show', $p) }}" class="btn btn-secondary btn-sm">👁</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:32px;color:var(--latte);">
                        ⚙️ Belum ada catatan produksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produksis->hasPages())
        <div class="card-body pagination-wrap">{{ $produksis->links() }}</div>
    @endif
</div>

@endsection
