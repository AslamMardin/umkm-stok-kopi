<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SCM Kopi UMKM</title>

    {{-- Google Fonts: Fraunces (display) + DM Sans (body) --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>

<body>

    {{-- ══════ SIDEBAR ══════ --}}
    <aside class="sidebar" id="sidebar">
        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">☕</div>
            <div class="sidebar-brand-title">Kopi Mapia</div>
            <div class="sidebar-brand-sub">SCM UMKM</div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-label">Utama</div>

                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </span> Dashboard
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-label">Master Data</div>

                <a href="{{ route('barang.index') }}"
                    class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                    <span class="icon">
                        <i class="fa-solid fa-box"></i>
                    </span> Barang

                </a>

                <a href="{{ route('supplier.index') }}"
                    class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                    <span class="icon">
                        <i class="fa-solid fa-handshake"></i>
                    </span> Supplier
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-label">Transaksi</div>

                <a href="{{ route('pembelian.index') }}"
                    class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
                    <span class="icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </span> Pembelian
                </a>

                <a href="{{ route('produksi.index') }}"
                    class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
                    <span class="icon">
                        <i class="fa-solid fa-industry"></i>
                    </span> Produksi
                </a>

                <a href="{{ route('penjualan.index') }}"
                    class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
                    <span class="icon">
                        <i class="fa-solid fa-cash-register"></i>
                    </span> Penjualan
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-label">Laporan</div>

                <a href="{{ route('laporan.index') }}"
                    class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <span class="icon">
                        <i class="fa-solid fa-file-lines"></i>
                    </span> Laporan
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
            @if (session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning">⚠️ {{ session('warning') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
