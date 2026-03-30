@extends('layouts.app')

@section('title', 'Manajemen Barang')
@section('page-title', 'Manajemen Barang')

@section('content')

{{-- Filter & Actions --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('barang.index') }}" method="GET"
              style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
            <div style="flex:1;min-width:180px;">
                <label class="form-label" style="margin-bottom:5px;">Cari Barang</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama barang..." class="form-control">
            </div>
            <div style="min-width:160px;">
                <label class="form-label" style="margin-bottom:5px;">Tipe</label>
                <select name="type" class="form-control">
                    <option value="">Semua Tipe</option>
                    <option value="bahan_mentah" {{ request('type') == 'bahan_mentah' ? 'selected' : '' }}>Bahan Mentah</option>
                    <option value="produk_jadi"  {{ request('type') == 'produk_jadi'  ? 'selected' : '' }}>Produk Jadi</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">  <i class="fa-solid fa-filter"></i>
 Cari</button>
            @if(request()->hasAny(['search','type']))
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">✕ Reset</a>
            @endif
            <div style="margin-left:auto;">
                <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Barang</div>
        <span class="text-muted">{{ $barangs->total() }} barang ditemukan</span>
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
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $i => $barang)
                <tr>
                    <td class="text-muted">{{ $barangs->firstItem() + $i }}</td>
                    <td style="font-weight:500;">{{ $barang->name }}</td>
                    <td>
                        <span class="badge badge-{{ $barang->type_badge }}">
                            {{ $barang->type_label }}
                        </span>
                    </td>
                    <td>{{ $barang->satuan }}</td>
                    <td style="text-align:right;font-weight:600;color:{{ $barang->stock <= 10 ? 'var(--danger)' : 'var(--espresso)' }};">
                        {{ number_format($barang->stock) }}
                        @if($barang->stock <= 10)
                            <span style="font-size:10px;color:var(--danger);">⚠️ menipis</span>
                        @endif
                    </td>
                    <td style="text-align:center;white-space:nowrap;">
                        <a href="{{ route('barang.show', $barang) }}"   class="btn btn-secondary btn-sm">👁</a>
                        <a href="{{ route('barang.edit', $barang) }}"   class="btn btn-secondary btn-sm">✏️</a>
                        <form action="{{ route('barang.destroy', $barang) }}" method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Hapus barang {{ addslashes($barang->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;color:var(--latte);">
                        📦 Belum ada barang. <a href="{{ route('barang.create') }}" style="color:var(--caramel);">Tambah sekarang</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($barangs->hasPages())
        <div class="card-body pagination-wrap">
            {{ $barangs->links() }}
        </div>
    @endif
</div>

@endsection
