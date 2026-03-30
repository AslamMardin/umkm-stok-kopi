@extends('layouts.app')
@section('title', 'Pembelian')
@section('page-title', 'Transaksi Pembelian')

@section('content')

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('pembelian.index') }}" method="GET"
              style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
            <div style="min-width:180px;">
                <label class="form-label" style="margin-bottom:5px;">Supplier</label>
                <select name="supplier_id" class="form-control">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" style="margin-bottom:5px;">Dari</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label" style="margin-bottom:5px;">Sampai</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary"> <i class="fa-solid fa-filter"></i> Filter</button>
            @if(request()->hasAny(['supplier_id','from','to']))
                <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">✕ Reset</a>
            @endif
            <div style="margin-left:auto;">
                <a href="{{ route('pembelian.create') }}" class="btn btn-primary">+ Catat Pembelian</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Pembelian</div>
        <span class="text-muted">{{ $pembelians->total() }} transaksi</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Barang</th>
                    <th style="text-align:right;">Qty</th>
                    <th style="text-align:right;">Harga/Satuan</th>
                    <th style="text-align:right;">Total</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelians as $i => $p)
                <tr>
                    <td class="text-muted">{{ $pembelians->firstItem() + $i }}</td>
                    <td>{{ $p->tanggal->format('d M Y') }}</td>
                    <td>{{ $p->supplier->name ?? '-' }}</td>
                    <td>{{ $p->barang->name ?? '-' }}</td>
                    <td style="text-align:right;">{{ $p->qty }} {{ $p->barang->satuan ?? '' }}</td>
                    <td style="text-align:right;">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-weight:600;">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td style="text-align:center;white-space:nowrap;">
                        <a href="{{ route('pembelian.show', $p) }}" class="btn btn-secondary btn-sm">👁</a>
                        <form action="{{ route('pembelian.destroy', $p) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Hapus transaksi ini? Stok barang akan dikembalikan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:32px;color:var(--latte);">
                        🛒 Belum ada transaksi pembelian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pembelians->hasPages())
        <div class="card-body pagination-wrap">{{ $pembelians->links() }}</div>
    @endif
</div>

@endsection
