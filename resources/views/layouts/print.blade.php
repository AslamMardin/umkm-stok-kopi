<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laporan') — Kopi Kurrak</title>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --roast:   #2c1a0e;
            --caramel: #a0522d;
            --latte:   #c8a882;
            --cream:   #fdf6ec;
            --success: #2e7d52;
            --danger:  #b91c1c;
            --warning: #b45309;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            background: #fff;
            padding: 20px 28px;
        }

        /* ── Header Laporan ── */
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2.5px solid var(--roast);
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .print-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .print-brand-icon {
            font-size: 28px;
            line-height: 1;
        }
        .print-brand-name {
            font-family: 'Fraunces', serif;
            font-size: 20pt;
            font-weight: 700;
            color: var(--roast);
        }
        .print-brand-sub {
            font-size: 8pt;
            color: var(--caramel);
            letter-spacing: .5px;
            text-transform: uppercase;
        }
        .print-meta {
            text-align: right;
            font-size: 9pt;
            color: #555;
            line-height: 1.6;
        }
        .print-meta strong {
            color: var(--roast);
            font-size: 10pt;
        }

        /* ── Judul Laporan ── */
        .print-title {
            font-family: 'Fraunces', serif;
            font-size: 16pt;
            font-weight: 700;
            color: var(--roast);
            margin-bottom: 4px;
        }
        .print-subtitle {
            font-size: 9.5pt;
            color: var(--caramel);
            margin-bottom: 16px;
        }

        /* ── Summary Cards ── */
        .summary-grid {
            display: grid;
            gap: 10px;
            margin-bottom: 16px;
        }
        .summary-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
        .summary-grid.cols-4 { grid-template-columns: repeat(4, 1fr); }

        .summary-card {
            border: 1px solid #e0d3c4;
            border-radius: 8px;
            padding: 10px 14px;
            background: var(--cream);
        }
        .summary-card .label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--caramel);
            margin-bottom: 4px;
        }
        .summary-card .value {
            font-family: 'Fraunces', serif;
            font-size: 18pt;
            font-weight: 700;
            color: var(--roast);
        }
        .summary-card.highlight .value { color: var(--success); }
        .summary-card.danger .value    { color: var(--danger); }
        .summary-card.warning .value   { color: var(--warning); }

        /* ── Total Banner ── */
        .total-banner {
            background: var(--cream);
            border: 1px solid #d9c9b4;
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-banner .label { font-size: 9.5pt; color: var(--caramel); }
        .total-banner .value {
            font-family: 'Fraunces', serif;
            font-size: 16pt;
            font-weight: 700;
            color: var(--roast);
        }

        /* ── Section Title ── */
        .section-title {
            font-weight: 600;
            font-size: 10.5pt;
            color: var(--roast);
            border-bottom: 1px solid #e2d5c3;
            padding-bottom: 5px;
            margin-bottom: 8px;
            margin-top: 16px;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        thead tr {
            background: var(--roast);
            color: white;
        }
        thead th {
            padding: 7px 10px;
            font-weight: 600;
            text-align: left;
            letter-spacing: .3px;
        }
        thead th.r { text-align: right; }
        thead th.c { text-align: center; }

        tbody tr { border-bottom: 1px solid #f0e8de; }
        tbody tr:nth-child(even) { background: #fdf8f2; }
        tbody td { padding: 6px 10px; vertical-align: middle; }
        tbody td.r { text-align: right; }
        tbody td.c { text-align: center; }

        tfoot tr { background: var(--cream); border-top: 1.5px solid var(--roast); }
        tfoot td { padding: 7px 10px; font-weight: 700; }
        tfoot td.r { text-align: right; }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .badge-success { background: #dcfce7; color: #166534; border-color: #86efac; }
        .badge-warning { background: #fef9c3; color: #854d0e; border-color: #fde68a; }
        .badge-danger  { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .badge-info    { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
        .badge-bahan   { background: #e0f2fe; color: #075985; border-color: #7dd3fc; }
        .badge-produk  { background: #f3e8ff; color: #6b21a8; border-color: #c4b5fd; }

        /* ── Footer ── */
        .print-footer {
            margin-top: 24px;
            border-top: 1px solid #e2d5c3;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 8.5pt;
            color: #888;
        }

        /* ── Signature area ── */
        .sign-area {
            display: flex;
            justify-content: flex-end;
            margin-top: 28px;
            gap: 40px;
        }
        .sign-box {
            text-align: center;
            width: 140px;
        }
        .sign-box .sign-label { font-size: 9pt; margin-bottom: 48px; }
        .sign-box .sign-line {
            border-top: 1px solid #333;
            padding-top: 4px;
            font-size: 8.5pt;
            color: #555;
        }

        /* ── Print Media ── */
        @media print {
            body { padding: 10px 16px; }
            @page { margin: 1.2cm 1.5cm; size: A4; }
        }

        /* ── Screen-only elements (tombol cetak, dll) ── */
        .no-print { display: block; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>

    {{-- Header Laporan --}}
    <div class="print-header">
        <div class="print-brand">
            <div class="print-brand-icon">☕</div>
            <div>
                <div class="print-brand-name">Kopi Kurrak</div>
                <div class="print-brand-sub">UMKM Manajemen Stok Kopi</div>
            </div>
        </div>
        <div class="print-meta">
            <strong>@yield('doc-title', 'Laporan')</strong><br>
            Dicetak: {{ now()->locale('id')->isoFormat('dddd, D MMMM Y · HH:mm') }}<br>
            Oleh: {{ auth()->user()->name ?? '-' }}
        </div>
    </div>

    {{-- Judul --}}
    <div class="print-title">@yield('doc-title', 'Laporan')</div>
    <div class="print-subtitle">@yield('doc-subtitle', '')</div>

    {{-- Tombol Cetak (hanya tampil di layar) --}}
    <div class="no-print" style="margin-bottom:16px;display:flex;gap:8px;flex-wrap:wrap;">
        <button onclick="window.print()"
            style="background:var(--roast);color:white;border:none;padding:9px 20px;border-radius:8px;font-size:11pt;font-family:'DM Sans',sans-serif;cursor:pointer;display:flex;align-items:center;gap:7px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;padding:9px 20px;border-radius:8px;font-size:11pt;font-family:'DM Sans',sans-serif;cursor:pointer;">
            ✕ Tutup
        </button>
    </div>

    @yield('content')

    {{-- Footer --}}
    <div class="print-footer">
        <span>Kopi Kurrak — Sistem Manajemen Stok UMKM</span>
        <span>Dokumen ini digenerate secara otomatis oleh sistem</span>
    </div>

</body>
</html>
