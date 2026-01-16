<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Review Pesanan</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; margin: 0; padding: 20px; color: #1a1a1a; }
        .card { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); }
        h2 { margin-top: 0; font-size: 18px; border-bottom: 1.5px solid #eee; padding-bottom: 15px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .order-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px dashed #eee; }
        .item-name { font-weight: 600; font-size: 15px; display: block; }
        .item-qty { font-size: 12px; color: #888; }
        .total-section { margin: 25px 0; font-size: 18px; font-weight: 800; display: flex; justify-content: space-between; border-top: 2px solid #eee; padding-top: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #666; }
        .form-control { width: 100%; padding: 14px; border: 1.5px solid #eee; border-radius: 12px; box-sizing: border-box; font-family: inherit; background: #fcfcfc; transition: 0.3s; }
        .form-control:focus { outline: none; border-color: #1a1a1a; background: white; }
        .btn-pay { width: 100%; padding: 18px; background: #1a1a1a; color: white; border: none; border-radius: 15px; font-weight: 700; cursor: pointer; margin-top: 15px; font-size: 16px; transition: 0.3s; }
        .btn-pay:disabled { background: #ccc; cursor: not-allowed; }
        .btn-pay:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .btn-back { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #888; font-size: 14px; font-weight: 600; }
        .btn-remove { background: #fff0f0; color: #ff4757; border: none; padding: 8px; border-radius: 8px; cursor: pointer; font-size: 14px; }
    </style>
</head>

<body>

    <div class="card">
        <h2>Review Pesanan (Meja: {{ session('table_id') }})</h2>

        <div id="cart-container">
            @forelse($cart as $id => $item)
            <div class="order-item" id="item-{{ $id }}">
                <div class="item-info">
                    <span class="item-name">{{ $item['name'] }}</span>
                    <span class="item-qty">{{ $item['quantity'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-weight:700;">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    <button type="button" class="btn-remove" onclick="handleRemove('{{ $id }}')">🗑️</button>
                </div>
            </div>
            @empty
            <p style="text-align:center; color:#888; padding: 20px 0;">Keranjang kosong bre.</p>
            @endforelse
        </div>

        <div class="total-section">
            <span>Total Bayar</span>
            <span id="total-display" style="color: #1a1a1a;">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        @if(count($cart) > 0)
        <div id="payment-form">
            <input type="hidden" id="table_id" value="{{ session('table_id') }}">
            <input type="hidden" id="total_val" value="{{ $total }}">

            <div class="form-group">
                <label>Nama Anda</label>
                <input type="text" id="customer_name" class="form-control" placeholder="Input nama sesuai KTP/e-wallet" required>
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="number" id="customer_phone" class="form-control" placeholder="Contoh: 0812xxxxxxxx" required>
            </div>

            <button type="button" id="pay-button" class="btn-pay">KONFIRMASI & BAYAR SEKARANG</button>
        </div>
        @endif

        <a href="{{ route('user.menu') }}" class="btn-back">← Tambah Menu Lain</a>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script>
        // 1. Fungsi Hapus Item
        function handleRemove(id) {
            if (confirm('Hapus menu ini dari keranjang?')) {
                fetch("{{ route('cart.remove') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') window.location.reload();
                });
            }
        }

        // 2. Fungsi Bayar (Sat-Set Pop-up)
        const payButton = document.getElementById('pay-button');
        if(payButton) {
            payButton.onclick = function() {
                const name = document.getElementById('customer_name').value;
                const phone = document.getElementById('customer_phone').value;
                const total = document.getElementById('total_val').value;
                const table_id = document.getElementById('table_id').value;

                if (!name || !phone) {
                    alert('Lengkapi Nama & No WhatsApp dulu bre!');
                    return;
                }

                this.innerHTML = "Processing...";
                this.disabled = true;

                // Tembak AJAX ke route baru
                fetch("{{ route('order.checkout') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: name,
                        phone: phone,
                        total: total,
                        table_id: table_id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snapToken) {
                        window.snap.pay(data.snapToken, {
                            onSuccess: function(result) { window.location.href = data.success_url; },
                            onPending: function(result) { window.location.reload(); },
                            onError: function(result) { window.location.reload(); },
                            onClose: function() { 
                                alert('Selesaikan pembayaranmu biar pesanan diproses!');
                                payButton.innerHTML = "KONFIRMASI & BAYAR SEKARANG";
                                payButton.disabled = false;
                            }
                        });
                    } else {
                        alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                        location.reload();
                    }
                })
                .catch(err => {
                    alert('Koneksi bermasalah bre!');
                    this.innerHTML = "KONFIRMASI & BAYAR SEKARANG";
                    this.disabled = false;
                });
            };
        }
    </script>
</body>
</html>