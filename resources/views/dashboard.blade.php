@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')@push('styles')
        <style>
        /* Container biar rapi */
.badge-container {
    display: inline-flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 1.5rem;
}

/* Khusus untuk stat dashboard */
.stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.08);
    color: inherit;
    font-weight: 600;
    line-height: 1;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}

.stat-badge i {
    font-size: 14px;
}

.stat-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(28, 18, 10, 0.08);
}

.stat-badge.stat-warning {
    border-color: rgba(255, 173, 61, 0.3);
    background: rgba(255, 173, 61, 0.12);
    color: var(--warning);
}
.stat-badge.stat-warning:hover {
    background: rgba(255, 173, 61, 0.2);
}

.stat-badge.stat-success {
    border-color: rgba(58, 176, 118, 0.3);
    background: rgba(58, 176, 118, 0.12);
    color: var(--success);
}
.stat-badge.stat-success:hover {
    background: rgba(58, 176, 118, 0.2);
}

.stat-badge.stat-info {
    border-color: rgba(82, 150, 255, 0.3);
    background: rgba(82, 150, 255, 0.12);
    color: var(--info);
}
.stat-badge.stat-info:hover {
    background: rgba(82, 150, 255, 0.2);
}

.stat-badge.stat-danger {
    border-color: rgba(229, 62, 62, 0.3);
    background: rgba(229, 62, 62, 0.12);
    color: var(--danger);
}
.stat-badge.stat-danger:hover {
    background: rgba(229, 62, 62, 0.2);
}
    </style>
@endpush
@section('content')

@if(auth()->user()->isAdminGudang())
    {{-- ══════ DASHBOARD ADMIN GUDANG ══════ --}}
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

    {{-- ══════ STAT CARDS ══════ --}}
    <div class="badge-container">
        <a href="{{ route('barang.index', ['type' => 'bahan_mentah']) }}" class="stat-badge stat-warning">
            <i class="fa-solid fa-box"></i>
            {{ $totalBahanMentah }} Bahan Mentah
        </a>

        <a href="{{ route('barang.index', ['type' => 'produk_jadi']) }}" class="stat-badge stat-success">
            <i class="fa-solid fa-box-open"></i>
            {{ $totalProdukJadi }} Produk Jadi
        </a>

        <a href="{{ route('supplier.index') }}" class="stat-badge stat-info">
            <i class="fa-solid fa-handshake"></i>
            {{ $totalSupplier }} Supplier
        </a>

        <a href="{{ route('pembelian.index') }}" class="stat-badge stat-warning">
            <i class="fa-solid fa-cart-shopping"></i>
            {{ $totalPembelian }} Pembelian
        </a>

        <a href="{{ route('produksi.index') }}" class="stat-badge stat-info">
            <i class="fa-solid fa-industry"></i>
            {{ $totalProduksi }} Produksi
        </a>

        <a href="{{ route('penjualan.index') }}" class="stat-badge stat-success">
            <i class="fa-solid fa-cash-register"></i>
            {{ $totalPenjualan }} Penjualan
        </a>

        @if($stokRendah > 0)
        <a href="{{ route('barang.index') }}" class="stat-badge stat-danger">
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ $stokRendah }} Stok Hampir Habis
        </a>
        @endif
    </div>

    {{-- ══════ BOTTOM ROW: Tables ══════ --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
        {{-- Penjualan Terbaru --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Penjualan</div>
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
            <div class="card-title">Pembelian</div>
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

@elseif(auth()->user()->isSupplier())
    {{-- ══════ DASHBOARD PETANI / SUPPLIER ══════ --}}
    <div class="card" style="margin-bottom: 26px;">
        <div class="card-body" style="display:flex;align-items:center;gap:16px;">
            <div style="font-size:36px;">🚜</div>
            <div>
                <div style="font-size:12px;color:var(--caramel);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Mitra Supplier</div>
                <div style="font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--roast);">
                    {{ auth()->user()->supplier->name ?? 'Petani Kopi' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Bahan Mentah --}}
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <div class="card-title">📦 Status Stok Bahan Mentah di Gudang</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Bahan Baku</th>
                        <th>Jumlah Stok Saat Ini</th>
                        <th>Satuan</th>
                        <th>Status Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanMentahDisuplai as $barang)
                    <tr>
                        <td style="font-weight: 600;">{{ $barang->name }}</td>
                        <td>{{ $barang->stock }}</td>
                        <td>{{ $barang->satuan }}</td>
                        <td>
                            @if($barang->stock <= 10)
                                <span class="badge badge-danger">Stok Menipis (Harap Pasok Ulang)</span>
                            @else
                                <span class="badge badge-success" style="background: var(--success); color: white;">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--latte);padding:20px;">
                            Belum ada stok bahan mentah di gudang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Riwayat Pasokan --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">⏳ Riwayat Pengiriman / Setoran Pasokan</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Bahan Baku</th>
                        <th>Kuantitas</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSetoran as $setor)
                    <tr>
                        <td>{{ $setor->tanggal->format('d M Y') }}</td>
                        <td>{{ $setor->barang->name ?? '-' }}</td>
                        <td>{{ $setor->qty }} {{ $setor->barang->satuan ?? '' }}</td>
                        <td>Rp {{ number_format($setor->harga_satuan, 0, ',', '.') }}</td>
                        <td style="font-weight: 600;">Rp {{ number_format($setor->qty * $setor->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $setor->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--latte);padding:20px;">
                            Belum ada riwayat setoran pasokan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@elseif(auth()->user()->isUmkm())
    {{-- ══════ DASHBOARD MITRA UMKM ══════ --}}
    <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:20px;">
        {{-- Stok Produk Jadi Ready untuk Beli --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🛍️ Produk Jadi Siap Pesan</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga Satuan</th>
                            <th>Stok Tersedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produkJadiReady as $barang)
                        @php
                            $hargaTerakhir = $barang->penjualans()->latest()->first()->harga_satuan ?? 50000;
                        @endphp
                        <tr>
                            <td style="font-weight:600;">{{ $barang->name }}</td>
                            <td>Rp {{ number_format($hargaTerakhir, 0, ',', '.') }}</td>
                            <td>
                                @if($barang->stock == 0)
                                    <span style="color: var(--danger); font-weight:600;">Habis</span>
                                @else
                                    {{ $barang->stock }} {{ $barang->satuan }}
                                @endif
                            </td>
                            <td>
                                @if($barang->stock > 0)
                                    <button type="button" class="btn btn-primary btn-sm" onclick="isiFormBeli({{ $barang->id }}, '{{ $barang->name }}', {{ $hargaTerakhir }})">
                                        Pesan
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Habis</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--latte);padding:20px;">
                                Belum ada produk jadi yang siap dibeli di gudang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Order Belanja --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Pemesanan Kopi</div>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div style="margin-bottom: 15px; padding: 10px 14px; border-radius: 8px; background-color: rgba(229, 62, 62, 0.1); border: 1px solid rgba(229, 62, 62, 0.25); color: #B23B3B; font-size: 13px;">
                        <ul style="margin: 0; padding-left: 16px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('umkm.beli') }}" method="POST" id="form-beli">
                    @csrf
                    <input type="hidden" name="barang_id" id="beli_barang_id">
                    
                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Produk Terpilih</label>
                        <input type="text" id="beli_barang_name" class="form-control" readonly placeholder="Klik 'Pesan' pada tabel" style="background:#f4f4f4;">
                    </div>

                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Harga Satuan (Rp)</label>
                        <input type="number" name="harga_satuan" id="beli_harga_satuan" class="form-control" readonly style="background:#f4f4f4;">
                    </div>

                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Jumlah Pesanan (Qty)</label>
                        <input type="number" name="qty" id="beli_qty" class="form-control" min="1" required placeholder="Masukkan jumlah">
                    </div>

                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Tanggal Pesanan</label>
                        <input type="date" name="tanggal" id="beli_tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Keterangan / Catatan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Kirim ke ruko UMKM"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">Konfirmasi Pemesanan</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Riwayat Belanja UMKM --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">⏳ Riwayat Pembelian Anda</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jumlah Belanja</th>
                        <th>Harga Satuan</th>
                        <th>Total Pengeluaran</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatBelanja as $belanja)
                    <tr>
                        <td>{{ $belanja->tanggal->format('d M Y') }}</td>
                        <td style="font-weight:600;">{{ $belanja->barang->name ?? '-' }}</td>
                        <td>{{ $belanja->qty }} {{ $belanja->barang->satuan ?? '' }}</td>
                        <td>Rp {{ number_format($belanja->harga_satuan, 0, ',', '.') }}</td>
                        <td style="font-weight:600;color:var(--success);">Rp {{ number_format($belanja->qty * $belanja->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $belanja->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--latte);padding:20px;">
                            Belum ada riwayat belanja. Silakan pilih produk untuk memesan!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        function isiFormBeli(id, name, harga) {
            document.getElementById('beli_barang_id').value = id;
            document.getElementById('beli_barang_name').value = name;
            document.getElementById('beli_harga_satuan').value = harga;
            document.getElementById('beli_qty').focus();
        }
    </script>
    @endpush
@endif

@endsection
