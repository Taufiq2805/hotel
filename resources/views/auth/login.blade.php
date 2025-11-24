<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grand Luxury Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold: #d4af37;
            --dark: #1a1a2e;
            --darker: #0f0f1e;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--darker) 0%, var(--dark) 50%, #16213e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            max-width: 1100px;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left Side */
        .login-left {
            background: linear-gradient(135deg, var(--darker), var(--dark));
            color: white;
            padding: 60px 40px;
        }

        .hotel-brand {
            text-align: center;
            margin-bottom: 50px;
        }

        .hotel-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--gold), #ffd700);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .hotel-icon i {
            font-size: 35px;
            color: var(--dark);
        }

        .hotel-brand h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--gold);
        }

        .hotel-brand p {
            font-size: 14px;
            opacity: 0.8;
            letter-spacing: 2px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.2s ease;
        }

        .feature-card:hover {
            transform: translateX(5px);
            border-color: var(--gold);
        }

        .feature-card i {
            font-size: 30px;
            color: var(--gold);
            margin-bottom: 15px;
        }

        .feature-card h5 {
            font-size: 18px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .feature-card small {
            opacity: 0.7;
            font-size: 13px;
        }

        /* Right Side */
        .login-right {
            padding: 60px 50px;
            background: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            color: var(--dark);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.2s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            background: white;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--gold);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--gold), #ffd700);
            color: var(--dark);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #999;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .login-left {
                display: none;
            }

            .login-right {
                padding: 40px 30px;
            }
        }

        @media (max-width: 576px) {
            .login-right {
                padding: 30px 20px;
            }

            .login-header h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side -->
        <div class="login-left">
            <div class="hotel-brand">
                <div class="hotel-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h1>GRAND LUXURY</h1>
                <p>HOTEL MANAGEMENT</p>
            </div>

            <div class="features">
                <div class="feature-card">
                    <i class="fas fa-bed"></i>
                    <h5>Manajemen Kamar Premium</h5>
                    <small>Kelola status kamar secara real-time dengan sistem terintegrasi</small>
                </div>

                <div class="feature-card">
                    <i class="fas fa-calendar-check"></i>
                    <h5>Reservasi Instan</h5>
                    <small>Proses booking cepat, mudah, dan efisien untuk kepuasan tamu</small>
                </div>

                <div class="feature-card">
                    <i class="fas fa-chart-line"></i>
                    <h5>Analisis Keuangan</h5>
                    <small>Dashboard lengkap untuk monitoring revenue dan occupancy rate</small>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="login-right">
            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p>Silakan login untuk mengakses sistem manajemen hotel</p>
            </div>

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label>Nama Gmail</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" class="form-control" placeholder="Masukkan nama" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>
                    <a href="#" class="forgot-link">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login Sekarang
                </button>
            </form>

            <div class="footer-text">
                &copy; 2025 Grand Luxury Hotel. All Rights Reserved.
            </div>
        </div>
    </div>
</body>
</html>