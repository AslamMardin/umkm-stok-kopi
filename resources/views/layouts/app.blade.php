<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SCM Kopi UMKM</title>

    {{-- Google Fonts: Fraunces (display) + DM Sans (body) --}}
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════
           CSS Variables — tema kopi hangat
        ═══════════════════════════════════════════════ */
        :root {
            --espresso:   #1C120A;
            --roast:      #3B2314;
            --caramel:    #8B5E3C;
            --latte:      #C49A6C;
            --cream:      #F5ECD7;
            --milk:       #FBF7F0;
            --white:      #FFFFFF;
            --success:    #3A7D44;
            --warning:    #C68B2F;
            --danger:     #B23B3B;
            --info:       #2E6DA4;
            --sidebar-w:  260px;
            --header-h:   64px;
            --radius:     10px;
            --shadow:     0 2px 12px rgba(28,18,10,.10);
            --transition: 0.2s ease;
        }

        /* ═══════════════════════════════════════════════
           Reset & Base
        ═══════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--milk);
            color: var(--espresso);
            min-height: 100vh;
            display: flex;
        }

        a { color: inherit; text-decoration: none; }

        /* ═══════════════════════════════════════════════
           Sidebar
        ═══════════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--espresso);
            color: var(--cream);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow-y: auto;
            z-index: 100;
            transition: transform var(--transition);
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand-icon {
            font-size: 28px;
            margin-bottom: 6px;
        }

        .sidebar-brand-title {
            font-family: 'Fraunces', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--cream);
            line-height: 1.2;
        }

        .sidebar-brand-sub {
            font-size: 11px;
            color: var(--latte);
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--latte);
            padding: 0 10px;
            margin-bottom: 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 400;
            color: rgba(245,236,215,.75);
            transition: all var(--transition);
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: var(--cream);
        }

        .nav-link.active {
            background: var(--caramel);
            color: var(--white);
            font-weight: 500;
        }

        .nav-link .icon { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: var(--caramel);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--cream);
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--cream);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: var(--latte);
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px;
            background: rgba(178,59,59,.2);
            border: 1px solid rgba(178,59,59,.3);
            border-radius: 8px;
            color: #e87070;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all var(--transition);
        }

        .btn-logout:hover { background: rgba(178,59,59,.35); color: #ff9090; }

        /* ═══════════════════════════════════════════════
           Main Content Area
        ═══════════════════════════════════════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: var(--header-h);
            background: var(--white);
            border-bottom: 1px solid rgba(59,35,20,.10);
            display: flex;
            align-items: center;
            padding: 0 28px;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Fraunces', serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--roast);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-date {
            font-size: 12px;
            color: var(--caramel);
        }

        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ═══════════════════════════════════════════════
           Alert / Flash Messages
        ═══════════════════════════════════════════════ */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 14px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success { background: #eaf4ec; border-color: var(--success); color: #2a5c32; }
        .alert-error   { background: #fbeaea; border-color: var(--danger);  color: #832c2c; }
        .alert-info    { background: #e8f0fb; border-color: var(--info);    color: #1e4d7b; }
        .alert-warning { background: #fdf3e3; border-color: var(--warning); color: #7a5319; }

        /* ═══════════════════════════════════════════════
           Cards
        ═══════════════════════════════════════════════ */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(59,35,20,.07);
        }

        .card-header {
            padding: 18px 22px 14px;
            border-bottom: 1px solid rgba(59,35,20,.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-family: 'Fraunces', serif;
            font-size: 17px;
            font-weight: 600;
            color: var(--roast);
        }

        .card-body { padding: 22px; }

        /* ═══════════════════════════════════════════════
           Tables
        ═══════════════════════════════════════════════ */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead th {
            background: var(--cream);
            color: var(--roast);
            font-weight: 600;
            font-size: 12px;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: 10px 14px;
            text-align: left;
            white-space: nowrap;
        }

        tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid rgba(59,35,20,.06);
            color: var(--espresso);
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--milk); }

        /* ═══════════════════════════════════════════════
           Buttons
        ═══════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            border: none;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .btn-primary   { background: var(--roast);   color: var(--cream);   }
        .btn-primary:hover { background: var(--espresso); }
        .btn-secondary { background: var(--cream);   color: var(--roast);   border: 1px solid rgba(59,35,20,.2); }
        .btn-secondary:hover { background: #ede4d1; }
        .btn-success   { background: var(--success); color: var(--white);   }
        .btn-danger    { background: var(--danger);  color: var(--white);   }
        .btn-danger:hover { background: #8f2e2e; }
        .btn-sm        { padding: 5px 11px; font-size: 12px; }

        /* ═══════════════════════════════════════════════
           Badges
        ═══════════════════════════════════════════════ */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .badge-warning { background: #fdf0d8; color: #7a5319; }
        .badge-success { background: #e5f3e7; color: #2a5c32; }
        .badge-danger  { background: #fbe8e8; color: #832c2c; }
        .badge-info    { background: #e5edf9; color: #1e4d7b; }

        /* ═══════════════════════════════════════════════
           Forms
        ═══════════════════════════════════════════════ */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--roast);
            margin-bottom: 6px;
        }
        .form-label .required { color: var(--danger); margin-left: 3px; }

        .form-control {
            width: 100%;
            padding: 9px 13px;
            border: 1.5px solid rgba(59,35,20,.2);
            border-radius: 7px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--espresso);
            background: var(--white);
            transition: border-color var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--caramel);
            box-shadow: 0 0 0 3px rgba(139,94,60,.12);
        }

        .form-control.is-invalid { border-color: var(--danger); }

        .invalid-feedback {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }

        .form-hint { font-size: 12px; color: var(--caramel); margin-top: 4px; }

        /* ═══════════════════════════════════════════════
           Pagination
        ═══════════════════════════════════════════════ */
        .pagination-wrap { margin-top: 18px; }
        .pagination-wrap nav { display: flex; justify-content: flex-end; }

        /* Override Laravel default pagination */
        .pagination-wrap .flex { gap: 4px; }
        .pagination-wrap a,
        .pagination-wrap span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            border: 1.5px solid rgba(59,35,20,.15);
            color: var(--roast);
            transition: all var(--transition);
        }

        .pagination-wrap a:hover { background: var(--cream); }
        .pagination-wrap [aria-current="page"] span,
        .pagination-wrap span[aria-current="page"] {
            background: var(--roast);
            color: var(--cream);
            border-color: var(--roast);
        }

        /* ═══════════════════════════════════════════════
           Stat Cards (for dashboard)
        ═══════════════════════════════════════════════ */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 26px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 18px 20px;
            border: 1px solid rgba(59,35,20,.08);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }

        .stat-card.brown::before   { background: var(--caramel); }
        .stat-card.green::before   { background: var(--success); }
        .stat-card.orange::before  { background: var(--warning); }
        .stat-card.red::before     { background: var(--danger);  }
        .stat-card.blue::before    { background: var(--info);    }

        .stat-icon { font-size: 28px; margin-bottom: 10px; }
        .stat-value {
            font-family: 'Fraunces', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--espresso);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--caramel);
            margin-top: 5px;
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════════
           Utilities
        ═══════════════════════════════════════════════ */
        .text-right { text-align: right; }
        .text-muted { color: var(--latte); font-size: 12px; }
        .d-flex     { display: flex; }
        .gap-2      { gap: 8px; }
        .gap-3      { gap: 12px; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .flex-wrap  { flex-wrap: wrap; }
        .mb-3       { margin-bottom: 16px; }
        .mb-4       { margin-bottom: 22px; }
        .mt-3       { margin-top: 16px; }
        .w-100      { width: 100%; }
        .font-mono  { font-family: monospace; }

        /* ═══════════════════════════════════════════════
           Responsive
        ═══════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .stat-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ══════ SIDEBAR ══════ --}}
<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">☕</div>
        <div class="sidebar-brand-title">SCM Kopi</div>
        <div class="sidebar-brand-sub">UMKM Management</div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-label">Utama</div>

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Master Data</div>

            <a href="{{ route('barang.index') }}"
               class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <span class="icon">📦</span> Barang
            </a>

            <a href="{{ route('supplier.index') }}"
               class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                <span class="icon">🤝</span> Supplier
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Transaksi</div>

            <a href="{{ route('pembelian.index') }}"
               class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
                <span class="icon">🛒</span> Pembelian
            </a>

            <a href="{{ route('produksi.index') }}"
               class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
                <span class="icon">⚙️</span> Produksi
            </a>

            <a href="{{ route('penjualan.index') }}"
               class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
                <span class="icon">💰</span> Penjualan
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Laporan</div>

            <a href="{{ route('laporan.index') }}"
               class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <span class="icon">📋</span> Laporan
            </a>
        </div>
    </nav>

    {{-- User & Logout --}}
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                🚪 Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ══════ MAIN WRAPPER ══════ --}}
<div class="main-wrapper">

    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-right">
            <span class="topbar-date">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">⚠️ {{ session('warning') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
