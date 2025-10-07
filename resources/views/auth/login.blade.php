<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
        }
        body {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: #fff;
            max-width: 900px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }
        .login-left, .login-right {
            flex: 1 1 50%;
            padding: 40px;
        }
        .login-left {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .hotel-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .hotel-logo i {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .hotel-logo h1 {
            font-weight: bold;
            font-size: 26px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .feature-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: var(--primary);
            font-weight: bold;
        }
        .role-selector {
            display: flex;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .role-option {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .role-option.active {
            background: var(--primary);
            color: white;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn-login:hover {
            background: var(--secondary);
        }
        .login-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-left {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Kiri -->
    <div class="login-left">
        <div class="hotel-logo">
            <i class="fas fa-hotel"></i>
            <h1>Grand Luxury Hotel</h1>
            <p>Sistem Manajemen Terintegrasi</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-bed"></i></div>
            <div>
                <h5>Manajemen Kamar</h5>
                <small>Kelola status kamar secara real-time</small>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <h5>Reservasi Mudah</h5>
                <small>Proses cepat & efisien</small>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
            <div>
                <h5>Laporan Keuangan</h5>
                <small>Pantau pemasukan & pengeluaran hotel</small>
            </div>
        </div>
    </div>

    <!-- Kanan -->
    <div class="login-right">
        <div class="login-header">
            <h2>Login Sistem</h2>
            <p>Masukkan email, password, dan role Anda</p>
        </div>

       <form method="POST" action="{{ route('login') }}" autocomplete="off">
    @csrf

    <!-- Role selector dan hidden input tetap -->

    <!-- Email -->
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               placeholder="Masukkan email" autocomplete="off" required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               placeholder="Masukkan password" autocomplete="off" required>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Remember -->
    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label for="remember" class="form-check-label">Ingat Saya</label>
    </div>

    <!-- Submit -->
    <button type="submit" class="btn btn-login">
        <i class="fas fa-sign-in-alt me-1"></i> Masuk
    </button>
</form>

        <div class="login-footer">
            &copy; {{ date('Y') }} Grand Luxury Hotel. All rights reserved.
        </div>
    </div>
</div>

<script>
    // Role selector
    document.querySelectorAll('.role-option').forEach(option => {
        option.addEventListener('click', () => {
            document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
            document.getElementById('role').value = option.dataset.role;
        });
    });
</script>
</body>
</html>
