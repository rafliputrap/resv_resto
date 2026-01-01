<!DOCTYPE html>
<html>
<head>
    <title>Terima Kasih</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f8f9fa; }
        .card { background: white; padding: 40px; border-radius: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h1 { color: #2ecc71; }
        p { color: #7f8c8d; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Terima Kasih!</h1>
        <p>Pesanan Anda telah kami terima dan sedang diproses.</p>
        <p>Silakan tunggu di meja Anda.</p>
        <hr>
        <small>Ingin pesan lagi? Klik tombol di bawah</small><br>
        <a href="{{ route('ask.table') }}" class="btn">Pesan Menu Lain</a>
    </div>
</body>
</html>