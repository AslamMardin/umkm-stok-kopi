@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan SCM')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;">

    <a href="{{ route('laporan.stok') }}" class="card" style="text-decoration:none;transition:transform .2s;cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
        <div class="card-body" style="text-align:center;padding:28px 20px;">
            <div style="font-size:44px;margin-bottom:12px;">📊</div>
            <div style="font-family:'Fraunces',serif;font-size:17px;font-weight:700;color:var(--roast);margin-bottom:6px;">Laporan Stok</div>
            <div style="font-size:13px;color:var(--caramel);">Kondisi stok semua barang saat ini</div>
        </div>
    </a>

    <a href="{{ route('laporan.pembelian') }}" class="card" style="text-decoration:none;transition:transform .2s;cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
        <div class="card-body" style="text-align:center;padding:28px 20px;">
            <div style="font-size:44px;margin-bottom:12px;">🛒</div>
            <div style="font-family:'Fraunces',serif;font-size:17px;font-weight:700;color:var(--roast);margin-bottom:6px;">Laporan Pembelian</div>
            <div style="font-size:13px;color:var(--caramel);">Rekapitulasi pembelian per periode</div>
        </div>
    </a>

    <a href="{{ route('laporan.produksi') }}" class="card" style="text-decoration:none;transition:transform .2s;cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
        <div class="card-body" style="text-align:center;padding:28px 20px;">
            <div style="font-size:44px;margin-bottom:12px;">⚙️</div>
            <div style="font-family:'Fraunces',serif;font-size:17px;font-weight:700;color:var(--roast);margin-bottom:6px;">Laporan Produksi</div>
            <div style="font-size:13px;color:var(--caramel);">Riwayat batch produksi per periode</div>
        </div>
    </a>

    <a href="{{ route('laporan.penjualan') }}" class="card" style="text-decoration:none;transition:transform .2s;cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
        <div class="card-body" style="text-align:center;padding:28px 20px;">
            <div style="font-size:44px;margin-bottom:12px;">💰</div>
            <div style="font-family:'Fraunces',serif;font-size:17px;font-weight:700;color:var(--roast);margin-bottom:6px;">Laporan Penjualan</div>
            <div style="font-size:13px;color:var(--caramel);">Rekapitulasi penjualan & pendapatan</div>
        </div>
    </a>

</div>
@endsection
