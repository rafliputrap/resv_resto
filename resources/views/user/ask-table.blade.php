<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            /* PATH DISESUAIKAN KE public/image */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url("{{ asset('image/bg-cafe.jpg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            z-index: 10;
        }

        .logo-placeholder {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -2px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        p {
            font-size: 1rem;
            margin-bottom: 40px;
            opacity: 0.8;
            font-weight: 400;
        }

        .button-stack {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .card-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 25px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .card-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.02);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            background: #ff9f43; /* Warna Orange Khas Hafa */
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(255, 159, 67, 0.3);
        }

        .text-box {
            text-align: left;
        }

        .text-box span {
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .text-box small {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 480px) {
            h1 { font-size: 1.5rem; }
            .card-btn { padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="logo-placeholder">Selamat Datang</div>
        <p>Experience the best warehouse</p>

        <div class="button-stack">
            <a href="{{ route('select.table', ['mode' => 'reorder']) }}" class="card-btn">
                <div class="icon-box">
                    <i class="fas fa-chair"></i>
                </div>
                <div class="text-box">
                    <span>Saya Sudah Ada Meja</span>
                    <small>Klik jika Anda ingin menambah pesanan</small>
                </div>
            </a>

            <a href="{{ route('select.table') }}" class="card-btn">
                <div class="icon-box" style="background: #ffffff; color: #1a1a1a;">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="text-box">
                    <span>Saya Belum Ada Meja</span>
                    <small>Pilih meja yang tersedia sekarang</small>
                </div>
            </a>
        </div>
    </div>

</body>
</html>