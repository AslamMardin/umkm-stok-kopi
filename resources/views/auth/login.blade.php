<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login SCM Kopi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>

<div class="login-box">
    <h2>☕ SCM Kopi</h2>
    <p>Supply Chain Management UMKM</p>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="admin@kopi.com" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>

    <div class="footer">
        © {{ date('Y') }} UMKM Kopi Polewali Mandar
    </div>
</div>

</body>
</html>