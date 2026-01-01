<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran - Meja {{ $reservation->table_id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; padding: 20px; text-align: center; background-color: #f8f9fa; }
        .card { border: 1px solid #ddd; padding: 30px; border-radius: 15px; display: inline-block; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .total { font-size: 28px; color: #2ecc71; margin: 20px 0; }
        #pay-button { background: #3498db; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 18px; font-weight: bold; width: 100%; }
        #pay-button:hover { background: #2980b9; }
    </style>
</head>
<body>

<div class="card">
    <h2>Selesaikan Pembayaran</h2>
    <p>Halo <b>{{ $reservation->customer_name }}</b>,</p>
    <p>Pesanan ID: #{{ $reservation->id }}</p>
    
    <p class="total">
        Total Bayar: <br>
        <b>Rp{{ number_format($reservation->total, 0, ',', '.') }}</b>
    </p>

    <button id="pay-button">BAYAR SEKARANG</button>
    
    <p style="margin-top: 20px; font-size: 12px; color: #7f8c8d;">
        *Klik tombol di atas untuk memilih metode pembayaran (QRIS, VA, dll)
    </p>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        // snapToken dikirim dari UserController
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                /* Pembayaran Berhasil */
                alert("Pembayaran Berhasil!");
                window.location.href = "{{ route('payment.success', $reservation->id) }}";
            },
            onPending: function (result) {
                /* Pembayaran Menunggu (User dapet nomor VA tapi belum bayar) */
                alert("Menunggu pembayaran Anda!");
                console.log(result);
            },
            onError: function (result) {
                /* Pembayaran Gagal */
                alert("Pembayaran Gagal!");
                console.log(result);
            },
            onClose: function () {
                /* User nutup pop-up Midtrans sebelum beres */
                alert('Anda menutup layar pembayaran sebelum selesai.');
            }
        });
    });
</script>

</body>
</html>