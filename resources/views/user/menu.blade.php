<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Wajib ada buat AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hafa Warehouse - Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            background: #fff;
            padding-bottom: 120px;
            color: #333;
        }

        .header-banner {
            width: 100%;
            height: 220px;
            background: url('{{ asset("image/warehouse-front.jpg") }}') no-repeat center;
            background-size: cover;
        }

        .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .shop-info h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .shop-info p {
            color: #888;
            margin: 5px 0 20px 0;
            font-size: 14px;
        }

        .category-title {
            font-size: 14px;
            font-weight: 700;
            margin: 25px 0 15px 0;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .menu-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
        }

        .menu-card img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            background: #f9f9f9;
        }

        .menu-info {
            padding: 12px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
            height: 34px;
            overflow: hidden;
        }

        .menu-price {
            color: #444;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .btn-tambah {
            width: 100%;
            padding: 8px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        /* Floating Cart Bar Style Opaper */
        .cart-bar {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 92%;
            max-width: 500px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .cart-left {
            display: flex;
            flex-direction: column;
        }

        .total-label {
            font-size: 11px;
            color: #888;
        }

        .total-amount {
            font-weight: 700;
            font-size: 16px;
            color: #2c3e50;
        }

        /* Tombol Lanjut */
        #btn-lanjut {
            padding: 10px 25px;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #e0e0e0;
            color: #a0a0a0;
            cursor: not-allowed;
        }

        #btn-lanjut.active {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="header-banner"></div>

    <div class="container">
        <div class="shop-info">
            <h2>Hafa Warehouse</h2>
            <p>Meja Nomor: <strong>{{ $table->table_number }}</strong></p>
        </div>

        @foreach($menus as $category => $items)
        <div class="category-title">{{ $category }}</div>
        <div class="menu-grid">
            @foreach($items as $m)
            <div class="menu-card">
                {{-- Memanggil folder public/image --}}
                <img src="{{ asset('image/' . $m->image) }}"
                    onerror="this.src='https://via.placeholder.com/150'">
                <div class="menu-info">
                    <div class="menu-name">{{ $m->name }}</div>
                    <div class="menu-price">Rp{{ number_format($m->price, 0, ',', '.') }}</div>
                    <button class="btn-tambah" data-id="{{ $m->id }}" onclick="handleTambah(this)">Tambah</button>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    {{-- Cart Bar --}}
    <div class="cart-bar">
        <div class="cart-left">
            <span class="total-label">Total Pesanan</span>
            <span class="total-amount">Rp<span id="total-display">{{ number_format($totalHarga, 0, ',', '.') }}</span></span>
        </div>
        <form action="{{ route('order.detail') }}" method="GET">
            <button type="submit"
                id="btn-lanjut"
                class="{{ $totalHarga > 0 ? 'active' : '' }}"
                {{ $totalHarga > 0 ? '' : 'disabled' }}>
                Lanjut
            </button>
        </form>
    </div>

    <script>
        function handleTambah(el) {
            const menuId = el.getAttribute('data-id');
            addToCart(menuId);
        }

        function addToCart(menuId) {
            // Ambil token CSRF dari meta tag (pastikan meta tag ada di <head>)
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        id: menuId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // UPDATE HARGA: Cari elemen id="total-display"
                        const totalElem = document.getElementById('total-display');
                        if (totalElem) {
                            totalElem.innerText = data.total_harga;
                        }

                        // NYALAIN TOMBOL: Cari elemen id="btn-lanjut"
                        const btn = document.getElementById('btn-lanjut');
                        if (btn) {
                            btn.disabled = false;
                            btn.classList.add('active');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Kalau error, paksa refresh biar token/session seger lagi
                    window.location.reload();
                });
        }
    </script>
</body>

</html>