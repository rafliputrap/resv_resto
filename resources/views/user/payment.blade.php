<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Review Pesanan - Hafa Warehouse</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #f8f9fa; --card: #ffffff; --primary: #1a1a1a; --text: #2d3436; }
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: var(--bg); margin: 0; padding: 20px; color: var(--text); }
        .container { max-width: 500px; margin: 0 auto; }
        
        .card { background: var(--card); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        h2 { font-size: 18px; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 15px; }

        /* List Pesanan */
        .order-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px dashed #eee; }
        .item-info { flex-grow: 1; }
        .item-name { font-weight: 600; font-size: 15px; }
        .item-qty { font-size: 12px; color: #636e72; }
        .item-price { font-weight: 700; }

        /* Form Input */
        .form-group { margin-top: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; }
        input { width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid #eee; background: #fcfcfc; font-size: 14px; outline: none; }
        input:focus { border-color: var(--primary); }

        .total-section { display: flex; justify-content: space-between; align-items: center; margin: 25px 0; font-size: 18px; font-weight: 800; }

        /* Buttons */
        .btn-pay { width: 100%; padding: 18px; background: var(--primary); color: white; border: none; border-radius: 15px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-pay:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        
        .btn-add-more { display: block; text-align: center; margin-top: 20px; color: #636e72; text-decoration: none; font-size: 14px; font-weight: 600; }
        .btn-add-more:hover { color: var(--primary); }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Detail Pesanan (Meja: {{ $table_number }})</h2>

        @foreach($cart as $id => $details)
        <div class="order-item">
            <div class="item-info">
                <div class="item-name">{{ $details['name'] }}</div>
                <div class="item-qty">{{ $details['quantity'] }}x Rp{{ number_format($details['price'], 0, ',', '.') }}</div>
            </div>
            <div class="item-price">Rp{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</div>
        </div>
        @endforeach

        <div class="total-section">
            <span>Total Bayar</span>
            <span style="color: var(--primary);">Rp{{ number_format($total_harga, 0, ',', '.') }}</span>
        </div>

        <div class="form-group">
            <label>Nama Anda</label>
            <input type="text" id="customer_name" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label>Nomor WhatsApp</label>
            <input type="number" id="customer_phone" placeholder="Contoh: 08123456789" required>
        </div>

        <button id="pay-button" class="btn-pay">KONFIRMASI & BAYAR SEKARANG</button>

        <a href="{{ route('menu.index') }}" class="btn-add-more">
            <i class="fas fa-plus-circle"></i> Tambah Menu Lain
        </a>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        const name = document.getElementById('customer_name').value;
        const phone = document.getElementById('customer_phone').value;

        if (!name || !phone) {
            alert('Harap isi Nama dan Nomor WhatsApp dulu, bre!');
            return;
        }

        // Jalankan AJAX untuk bikin transaksi dan dapetin Snap Token
        fetch("{{ route('order.checkout') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ name: name, phone: phone })
        })
        .then(res => res.json())
        .then(data => {
            if(data.snapToken) {
                window.snap.pay(data.snapToken, {
                    onSuccess: function (result) {
                        window.location.href = data.success_url;
                    },
                    onPending: function (result) {
                        alert("Menunggu pembayaran Anda!");
                    },
                    onError: function (result) {
                        alert("Pembayaran Gagal!");
                    }
                });
            } else {
                alert("Error: " + data.message);
            }
        });
    });
</script>

</body>
</html>