<!DOCTYPE html>
<html>
<head>
    <title>Form Reservasi</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        input { display: block; margin: 10px 0; padding: 8px; width: 250px; }
        button { padding: 10px 20px; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Reservasi Meja</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form action="{{ url('/reservasi') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="no_hp" placeholder="No HP" required>
        <button type="submit">Reservasi</button>
    </form>
</body>
</html>
