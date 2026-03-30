@extends('layouts.app')

@section('title', 'Supplier')
@section('page-title', 'Manajemen Supplier')

@section('content')

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('supplier.index') }}" method="GET"
              style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label" style="margin-bottom:5px;">Cari Supplier</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama atau nomor telepon..." class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if(request('search'))
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">✕ Reset</a>
            @endif
            <div style="margin-left:auto;">
                <a href="{{ route('supplier.create') }}" class="btn btn-primary">+ Tambah Supplier</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Supplier</div>
        <span class="text-muted">{{ $suppliers->total() }} supplier</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Supplier</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th style="text-align:center;">Total Pembelian</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $i => $s)
                <tr>
                    <td class="text-muted">{{ $suppliers->firstItem() + $i }}</td>
                    <td style="font-weight:500;">{{ $s->name }}</td>
                    <td>{{ $s->phone ?? '—' }}</td>
                    <td>{{ $s->email ?? '—' }}</td>
                    <td style="text-align:center;">
                        <span class="badge badge-info">{{ $s->pembelians_count }} transaksi</span>
                    </td>
                    <td style="text-align:center;white-space:nowrap;">
                        <a href="{{ route('supplier.show', $s) }}" class="btn btn-secondary btn-sm">👁</a>
                        <a href="{{ route('supplier.edit', $s) }}" class="btn btn-secondary btn-sm">✏️</a>
                        <form action="{{ route('supplier.destroy', $s) }}" method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Hapus supplier {{ addslashes($s->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;color:var(--latte);">
                        🤝 Belum ada supplier. <a href="{{ route('supplier.create') }}" style="color:var(--caramel);">Tambah sekarang</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suppliers->hasPages())
        <div class="card-body pagination-wrap">{{ $suppliers->links() }}</div>
    @endif
</div>

@endsection
