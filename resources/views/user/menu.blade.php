<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hafa Warehouse - Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a1a1a;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            margin: 0;
            background: var(--bg-body);
            padding-bottom: 120px;
            color: var(--text-main);
        }

        .container {
            padding: 16px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* HEADER BANNER DENGAN OVERLAY PUTIH TRANSPARAN */
        .header-banner {
            width: 100%;
            height: 280px;
            /* 1. Sesuaikan nama file ke menu-bg.png */
            /* 2. Overlay putih tipis (0.4 = 40% putih) untuk menutupi gambar bata */
            background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2)),
            url("{{ asset('image/bg-menu.png') }}") no-repeat center;
            background-size: cover;
            background-position: center 20%;
            position: relative;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .shop-info {
            background: var(--card-bg);
            padding: 30px 20px;
            border-radius: 24px;
            margin: -60px 16px 0 16px;
            position: relative;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .shop-info h2 {
            margin: 0;
            font-weight: 800;
            font-size: 24px;
            letter-spacing: -0.5px;
        }

        .category-title {
            font-size: 16px;
            font-weight: 800;
            margin: 40px 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .category-title::after {
            content: "";
            height: 2px;
            background: #e2e8f0;
            flex: 1;
        }

        /* Grid Menu */
        .menu-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, 1fr);
        }

        @media (min-width: 768px) {
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .menu-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Card Style */
        .menu-card {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .menu-card.oos {
            filter: grayscale(1);
            opacity: 0.6;
            cursor: not-allowed;
        }

        .menu-card img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
        }

        .badge-oos {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 12px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 800;
            z-index: 5;
        }

        .menu-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-name {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 6px;
            color: #1e293b;
        }

        .menu-price {
            font-weight: 800;
            color: var(--primary-color);
            font-size: 16px;
            margin-bottom: 12px;
        }

        .btn-tambah {
            width: 100%;
            padding: 12px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            color: #475569;
        }

        .btn-tambah:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background: #fff;
            width: 90%;
            max-width: 420px;
            border-radius: 30px;
            overflow: hidden;
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Bottom Bar */
        .cart-bar {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 92%;
            max-width: 500px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 25px;
            border-radius: 22px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            z-index: 999;
            border: 1px solid #f1f5f9;
        }
    </style>
</head>

<body>
    <div class="header-banner"></div>
    <div class="container">
        <div class="shop-info">
            <h2>Hafa Warehouse</h2>
            <p style="margin:8px 0 0; color:var(--text-muted); font-size: 14px;">Meja: <strong>{{ $table->table_number }}</strong></p>
        </div>

        @foreach($menus as $category => $items)
        <div class="category-title">{{ $category }}</div>
        <div class="menu-grid">
            @foreach($items as $m)
            @php $imagePath = asset("image/$m->image"); @endphp
            <div class="menu-card {{ $m->stock <= 0 ? 'oos' : '' }}"
                @if($m->stock > 0) onclick="openModal('{{ $m->name }}', '{{ $imagePath }}', '{{ $m->price }}', '{{ addslashes($m->description) }}', '{{ $m->id }}')" @endif>

                @if($m->stock <= 0) <div class="badge-oos">HABIS</div> @endif

            <img src="{{ $imagePath }}" onerror="this.src='https://via.placeholder.com/300'">
            <div class="menu-info">
                <div class="menu-name">{{ $m->name }}</div>
                <div class="menu-price">Rp{{ number_format($m->price, 0, ',', '.') }}</div>
                @if($m->stock > 0)
                <button class="btn-tambah" onclick="event.stopPropagation(); handleTambah(this)" data-id="{{ $m->id }}">Tambah</button>
                @else
                <button class="btn-habis" disabled style="width: 100%; padding: 12px; border-radius: 14px; border: none; background: #f1f5f9; color: #94a3b8; font-weight: 700;">Stok Habis</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    </div>

    <div id="menuModal" class="modal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <img id="modalImg" style="width:100%; height:300px; object-fit:cover">
            <div style="padding:25px">
                <h2 id="modalName" style="margin:0 0 10px; font-weight: 800;"></h2>
                <p id="modalDesc" style="color:var(--text-muted); font-size:14px; line-height: 1.6; margin-bottom:25px"></p>
                <div class="d-flex justify-content-between align-items-center">
                    <div id="modalPrice" style="font-size:22px; font-weight:800; color:var(--primary-color)"></div>
                </div>
                <button class="btn-tambah" style="background:var(--primary-color); color:#fff; padding:16px; margin-top: 20px; border: none;" onclick="addFromModal()">Tambah ke Pesanan</button>
            </div>
        </div>
    </div>

    <div class="cart-bar">
        <div>
            <div style="font-size:11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Total Pesanan</div>
            <div style="font-size:20px; font-weight:800">Rp<span id="total-display">{{ number_format($totalHarga, 0, ',', '.') }}</span></div>
        </div>
        <form action="{{ route('order.detail') }}" method="GET">
            <button type="submit" id="btn-lanjut" class="btn-tambah" {{ $totalHarga > 0 ? '' : 'disabled' }}
                style="width:auto; padding:12px 35px; border-radius: 16px; background:{{ $totalHarga > 0 ? 'var(--primary-color)' : '#f1f5f9' }}; color:{{ $totalHarga > 0 ? '#fff' : '#94a3b8' }}; border:none; font-size: 15px;">Lanjut</button>
        </form>
    </div>

    <script>
        let currentMenuId = null;

        function openModal(name, img, price, desc, id) {
            currentMenuId = id;
            document.getElementById('modalName').innerText = name;
            document.getElementById('modalImg').src = img;
            document.getElementById('modalPrice').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(price);
            document.getElementById('modalDesc').innerText = desc || "Kelezatan otentik dari dapur Hafa Warehouse.";
            document.getElementById('menuModal').style.display = 'flex';
        }

        function closeModal(e) {
            if (e.target.id === 'menuModal') document.getElementById('menuModal').style.display = 'none';
        }

        function closeModalDirect() {
            document.getElementById('menuModal').style.display = 'none';
        }

        function handleTambah(el) {
            const menuId = el.getAttribute('data-id');
            addToCart(menuId, el);
        }

        function addFromModal() {
            if (currentMenuId) {
                addToCart(currentMenuId, null);
                closeModalDirect();
            }
        }

        function addToCart(menuId, btnEl) {
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
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('total-display').innerText = data.total_harga;
                        const btnLanjut = document.getElementById('btn-lanjut');
                        btnLanjut.disabled = false;
                        btnLanjut.style.background = 'var(--primary-color)';
                        btnLanjut.style.color = '#fff';
                        if (btnEl) {
                            btnEl.innerHTML = '✓ Terpilih';
                            btnEl.style.background = '#dcfce7';
                            btnEl.style.color = '#166534';
                            btnEl.style.borderColor = '#bbf7d0';
                            setTimeout(() => {
                                btnEl.innerText = 'Tambah';
                                btnEl.style.background = '#f8fafc';
                                btnEl.style.color = '#475569';
                                btnEl.style.borderColor = '#e2e8f0';
                            }, 1000);
                        }
                    } else {
                        alert(data.message);
                        location.reload();
                    }
                });
        }
    </script>
</body>

</html>