@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ══════ STAT CARDS ══════ --}}
<div class="stat-grid">
    <div class="stat-card brown">
        <div class="stat-icon">🌱</div>
        <div class="stat-value">{{ $totalBahanMentah }}</div>
        <div class="stat-label">Jenis Bahan Mentah</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">📦</div>
        <div class="stat-value">{{ $totalProdukJadi }}</div>
        <div class="stat-label">Jenis Produk Jadi</div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon">🤝</div>
        <div class="stat-value">{{ $totalSupplier }}</div>
        <div class="stat-label">Supplier Aktif</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">🛒</div>
        <div class="stat-value">{{ $totalPembelian }}</div>
        <div class="stat-label">Total Pembelian</div>
    </div>

    <div class="stat-card brown">
        <div class="stat-icon">⚙️</div>
        <div class="stat-value">{{ $totalProduksi }}</div>
        <div class="stat-label">Batch Produksi</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">💰</div>
        <div class="stat-value">{{ $totalPenjualan }}</div>
        <div class="stat-label">Total Penjualan</div>
    </div>

    @if($stokRendah > 0)
    <div class="stat-card red">
        <div class="stat-icon">⚠️</div>
        <div class="stat-value">{{ $stokRendah }}</div>
        <div class="stat-label">Stok Hampir Habis</div>
    </div>
    @endif
</div>

{{-- ══════ NILAI RINGKAS ══════ --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:26px;">
    <div class="card">
        <div class="card-body" style="display:flex;align-items:center;gap:16px;">
            <div style="font-size:36px;">📥</div>
            <div>
                <div style="font-size:12px;color:var(--caramel);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Total Nilai Pembelian</div>
                <div style="font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--roast);">
                    Rp {{ number_format($totalNilaiPembelian, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="display:flex;align-items:center;gap:16px;">
            <div style="font-size:36px;">📤</div>
            <div>
                <div style="font-size:12px;color:var(--caramel);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Total Nilai Penjualan</div>
                <div style="font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--success);">
                    Rp {{ number_format($totalNilaiPenjualan, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════ BOTTOM ROW: Tables ══════ --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

    {{-- Penjualan Terbaru --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Penjualan Terbaru</div>
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Tgl</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualanTerbaru as $p)
                    <tr>
                        <td>{{ $p->barang->name ?? '-' }}</td>
                        <td>{{ $p->qty }}</td>
                        <td style="white-space:nowrap;">Rp {{ number_format($p->qty * $p->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-muted">{{ $p->tanggal->format('d/m/y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--latte);padding:20px;">
                            Belum ada penjualan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Stok Rendah --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">⚠️ Stok Menipis</div>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">Lihat Barang</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Tipe</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangStokRendah as $b)
                    <tr>
                        <td>{{ $b->name }}</td>
                        <td>
                            <span class="badge badge-{{ $b->type_badge }}">
                                {{ $b->type_label }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:600;color:{{ $b->stock <= 5 ? 'var(--danger)' : 'var(--warning)' }};">
                                {{ $b->stock }} {{ $b->satuan }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:var(--latte);padding:20px;">
                            ✅ Semua stok aman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pembelian Terbaru --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Pembelian Terbaru</div>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelianTerbaru as $p)
                <tr>
                    <td>{{ $p->supplier->name ?? '-' }}</td>
                    <td>{{ $p->barang->name ?? '-' }}</td>
                    <td>{{ $p->qty }} {{ $p->barang->satuan ?? '' }}</td>
                    <td>Rp {{ number_format($p->qty * $p->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-muted">{{ $p->tanggal->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--latte);padding:20px;">
                        Belum ada pembelian
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
