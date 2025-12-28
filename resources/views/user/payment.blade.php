<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
</head>
<body>

<h2>Detail Pembayaran</h2>

<p>Total Bayar:
    <b>Rp{{ number_format($total) }}</b>
</p>

<form method="POST" action="/payment">
    @csrf

    <input type="hidden" name="table_id" value="{{ session('table_id') }}">
    <input type="hidden" name="total" value="{{ $total }}">

    <input type="text" name="customer_name" placeholder="Nama" required>
    <br><br>
    <input type="text" name="phone" placeholder="No HP" required>
    <br><br>

    <button type="submit">Bayar & Reservasi</button>
</form>

</body>
</html>
