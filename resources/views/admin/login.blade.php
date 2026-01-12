<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hafa Warehouse</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Ganti ke bg-cafe sesuai request lo */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), 
                        url("{{ asset('image/bg-cafe.jpg') }}") no-repeat center;
            background-size: cover;
            background-attachment: fixed;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 25px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 45px 35px;
            border-radius: 35px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            background: #1a1a1a;
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .login-card h2 {
            margin: 0 0 8px;
            font-weight: 800;
            font-size: 26px;
            color: #1a1a1a;
            letter-spacing: -1px;
        }

        .login-card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
            text-align: left;
        }

        .form-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
        }

        .form-control {
            width: 100%;
            padding: 16px 16px 16px 52px;
            border-radius: 18px;
            border: 2px solid #f1f5f9;
            background: #f8fafc;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            color: #1e293b;
        }

        .form-control:focus {
            border-color: #1a1a1a;
            background: #fff;
            box-shadow: 0 0 0 5px rgba(26, 26, 26, 0.08);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            border-radius: 18px;
            border: none;
            background: #1a1a1a;
            color: white;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: #000;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .error-msg {
            background: #fff1f2;
            color: #e11d48;
            padding: 14px;
            border-radius: 16px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 25px;
            border: 1px solid #ffe4e6;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="brand-logo">
            <i class="fas fa-warehouse"></i>
        </div>
        <h2>Admin Login</h2>
        <p>Hafa Warehouse Management System</p>

        @if(session('error'))
            <div class="error-msg">
                <i class="fas fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf
            <div class="form-group">
                <i class="fas fa-at"></i>
                <input type="email" name="email" class="form-control" placeholder="Email Admin" required>
            </div>

            <div class="form-group">
                <i class="fas fa-shield-halved"></i>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-login">SIGN IN</button>
        </form>
    </div>
</div>

</body>
</html>