<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SCM Kopi Polewali Mandar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #3d1a00 0%, #7b3f00 50%, #c47e2b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        .login-header {
            background: #3d1a00;
            border-radius: 16px 16px 0 0;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control:focus {
            border-color: #7b3f00;
            box-shadow: 0 0 0 0.2rem rgba(61,26,0,0.2);
        }
        .btn-login {
            background: #3d1a00;
            border-color: #3d1a00;
            color: #fff;
            font-weight: 600;
            padding: 0.65rem;
        }
        .btn-login:hover {
            background: #5c3017;
            border-color: #5c3017;
            color: #fff;
        }
        .input-group-text {
            background: #f8f5f0;
            border-color: #dee2e6;
            color: #7b3f00;
        }
    </style>
</head>
<body>
    <div class="login-card card">
        {{-- Header --}}
        <div class="login-header">
            <div style="font-size: 3rem;">☕</div>
            <h4 class="text-white fw-bold mb-1 mt-2">SCM Kopi PWM</h4>
            <p class="mb-0" style="color:#d4a97a; font-size:0.85rem;">
                Sistem Informasi Supply Chain<br>UMKM Kopi Polewali Mandar
            </p>
        </div>

        {{-- Body --}}
        <div class="login-body">
            <h6 class="fw-bold text-center mb-4" style="color:#3d1a00;">Masuk ke Sistem</h6>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="admin@kopi.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            required
                        >
                        <button class="btn btn-outline-secondary" type="button" id="toggle-password" tabindex="-1">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>
        </div>

        <div class="card-footer text-center py-3" style="background:#f8f5f0; border-radius:0 0 16px 16px;">
            <small class="text-muted">
                &copy; {{ date('Y') }} SCM Kopi Polewali Mandar
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            const pwd  = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type  = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pwd.type  = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    </script>
</body>
</html>
