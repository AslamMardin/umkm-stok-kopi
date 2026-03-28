<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCM Kopi PWM - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f5f0; }
        .sidebar { background: #3d1a00; min-height: 100vh; }
        .sidebar .nav-link { color: #d4a97a; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); border-radius: 6px; }
        .sidebar-brand { color: #f0c87a; font-size: 1.1rem; font-weight: bold; }
        .badge-kritis { background-color: #dc3545; }
        .card-stok { border-left: 4px solid #3d1a00; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-md-block sidebar py-3 px-2">
            <div class="sidebar-brand text-center mb-4">
                <i class="bi bi-cup-hot-fill"></i> SCM Kopi PWM
                <div class="small fw-normal" style="color:#d4a97a;font-size:0.75rem;">Polewali Mandar</div>
            </div>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <small class="text-muted px-2" style="color:#9e7a5a !important;">RANTAI PASOK</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('pembelian.*')) active @endif" href="{{ route('pembelian.index') }}">
                        <i class="bi bi-cart3 me-2"></i> Pembelian BB
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('produksi.*')) active @endif" href="{{ route('produksi.index') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i> Produksi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('penjualan.*')) active @endif" href="{{ route('penjualan.index') }}">
                        <i class="bi bi-bag-check me-2"></i> Penjualan
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <small class="text-muted px-2" style="color:#9e7a5a !important;">INVENTORI</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('stok.*')) active @endif" href="{{ route('stok.index') }}">
                        <i class="bi bi-boxes me-2"></i> Monitor Stok
                    </a>
                </li>
            </ul>
            <div class="mt-4 px-2">
                <div class="small" style="color:#9e7a5a;">
                    Login sebagai:<br>
                    <strong style="color:#d4a97a;">{{ auth()->user()->name }}</strong>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-outline-warning w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 py-4 px-4">
            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
