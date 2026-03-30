<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SCM Kopi UMKM</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400i&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --espresso: #1C120A;
            --roast:    #3B2314;
            --caramel:  #8B5E3C;
            --latte:    #C49A6C;
            --cream:    #F5ECD7;
            --milk:     #FBF7F0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--roast);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Rich coffee texture via CSS gradient */
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(139,94,60,.4) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(28,18,10,.6) 0%, transparent 50%);
            padding: 20px;
        }

        .login-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 860px;
            width: 100%;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.5);
        }

        /* Left panel — decorative */
        .login-left {
            background: var(--espresso);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139,94,60,.35) 0%, transparent 70%);
            top: -80px; right: -80px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196,154,108,.2) 0%, transparent 70%);
            bottom: -50px; left: -50px;
        }

        .brand-block { position: relative; z-index: 1; }

        .brand-icon {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }

        .brand-name {
            font-family: 'Fraunces', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--cream);
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .brand-tagline {
            font-size: 14px;
            color: var(--latte);
            line-height: 1.6;
        }

        .feature-list {
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(245,236,215,.65);
            font-size: 13px;
            margin-bottom: 10px;
        }

        .feature-item::before {
            content: '✓';
            width: 20px; height: 20px;
            background: rgba(139,94,60,.4);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            color: var(--latte);
            flex-shrink: 0;
        }

        .login-year {
            font-size: 11px;
            color: rgba(196,154,108,.4);
            position: relative;
            z-index: 1;
        }

        /* Right panel — form */
        .login-right {
            background: var(--milk);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-family: 'Fraunces', serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--roast);
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--caramel);
            margin-bottom: 32px;
        }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--roast);
            margin-bottom: 7px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid rgba(59,35,20,.2);
            border-radius: 9px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--espresso);
            background: white;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--caramel);
            box-shadow: 0 0 0 3px rgba(139,94,60,.13);
        }

        .form-control.is-invalid { border-color: #B23B3B; }

        .invalid-feedback {
            color: #B23B3B;
            font-size: 12px;
            margin-top: 5px;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .checkbox-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--caramel);
            cursor: pointer;
        }

        .checkbox-row label {
            font-size: 13px;
            color: var(--caramel);
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--roast);
            color: var(--cream);
            border: none;
            border-radius: 9px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background .2s, transform .1s;
        }

        .btn-login:hover   { background: var(--espresso); }
        .btn-login:active  { transform: scale(.98); }

        .alert-danger {
            background: #fbeaea;
            border: 1px solid #e8b0b0;
            border-left: 4px solid #B23B3B;
            border-radius: 8px;
            padding: 11px 14px;
            color: #832c2c;
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .login-wrap { grid-template-columns: 1fr; }
            .login-left { display: none; }
            .login-right { padding: 36px 28px; }
        }
    </style>
</head>
<body>
    <div class="login-wrap">

        {{-- ── Left: Branding ── --}}
        <div class="login-left">
            <div class="brand-block">
                <span class="brand-icon">☕</span>
                <div class="brand-name">SCM Kopi<br>UMKM</div>
                <p class="brand-tagline">
                    Sistem informasi manajemen rantai pasok untuk usaha kopi Anda — dari biji mentah hingga cangkir pelanggan.
                </p>
            </div>

            <div class="feature-list">
                <div class="feature-item">Kelola stok bahan mentah & produk jadi</div>
                <div class="feature-item">Catat pembelian dari supplier</div>
                <div class="feature-item">Monitor proses produksi</div>
                <div class="feature-item">Lacak penjualan & pendapatan</div>
                <div class="feature-item">Laporan ringkasan SCM</div>
            </div>

            <div class="login-year">© {{ date('Y') }} SCM Kopi UMKM</div>
        </div>

        {{-- ── Right: Form ── --}}
        <div class="login-right">
            <div class="login-title">Selamat Datang</div>
            <div class="login-subtitle">Masuk ke panel manajemen SCM Anda</div>

            {{-- Error Alert --}}
            @if($errors->any())
                <div class="alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Info Alert (setelah logout) --}}
            @if(session('info'))
                <div style="background:#e8f0fb;border:1px solid #b3c8ec;border-left:4px solid #2E6DA4;border-radius:8px;padding:11px 14px;color:#1e4d7b;font-size:13px;margin-bottom:20px;">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" novalidate>
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@kopi.com"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        autocomplete="email"
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        autocomplete="current-password"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="checkbox-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">Masuk →</button>
            </form>
        </div>
    </div>
</body>
</html>
