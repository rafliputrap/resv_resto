<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pesanan - Hafa Warehouse</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .card {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        h2 {
            margin-top: 0;
            font-size: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #fafafa;
        }

        .item-name {
            font-weight: 600;
            font-size: 15px;
        }

        .item-qty {
            font-size: 13px;
            color: #888;
        }

        .total-section {
            margin: 20px 0;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            justify-content: space-between;
            border-top: 2px solid #eee;
            padding-top: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
        }

        .btn-pay {
            width: 100%;
            padding: 15px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            font-size: 15px;
        }

        .btn-pay:hover {
            background: #333;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Detail Pesanan (Meja: {{ session('table_id') }})</h2>

        <div id="cart-container">
            @forelse($cart as $id => $item)
            <div class="order-item" id="item-{{ $id }}">
                <div class="item-info">
                    <span class="item-name">{{ $item['name'] }}</span>
                    <span class="item-qty">{{ $item['quantity'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span class="item-subtotal" style="font-weight:700;">
                        Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                    </span>
                    <button type="button" onclick="handleRemove('{{ $id }}')" style="background:none; border:none; font-size: 18px; cursor:pointer;">🗑️</button>
                </div>
            </div>
            @empty
            <p style="text-align:center; color:#888;">Keranjang kosong bre.</p>
            @endforelse
        </div>

        <div class="total-section">
            <span>Total Bayar</span>
            <span id="total-display">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        @if(count($cart) > 0)
        {{-- Point Utama: Method POST ke route payment.store --}}
        <form action="{{ route('payment.store') }}" method="POST">
            @csrf
            {{-- Tambahkan input hidden ini agar ID Meja ikut terkirim --}}
            <input type="hidden" name="table_id" value="{{ session('table_id') }}">

            <div class="form-group">
                <label>Nama Anda</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="number" name="phone" class="form-control" required>
            </div>

            <input type="hidden" name="total" value="{{ $total }}">
            <button type="submit" class="btn-pay">KONFIRMASI PESANAN</button>
        </form>
        @endif

        <a href="{{ route('user.menu') }}" class="btn-back">← Tambah Menu Lain</a>
    </div>

    <script>
        function handleRemove(id) {
            if (confirm('Hapus menu ini?')) {
                fetch("{{ route('cart.remove') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.location.reload(); // Reload agar total terupdate
                        }
                    })
                    .catch(error => alert('Gagal menghapus item'));
            }
        }
    </script>

</body>

</html>