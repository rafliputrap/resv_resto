<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Meja</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body { 
            /* BACKGROUND SAMA DENGAN HALAMAN ASK TABLE */
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), 
                        url("{{ asset('image/bg-cafe.jpg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
        }

        h2 { 
            margin-bottom: 30px; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 2px;
            text-align: center;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        }

        .main-wrapper { 
            display: flex; 
            flex-direction: row; 
            justify-content: center; 
            align-items: flex-start; 
            gap: 30px; 
            max-width: 1100px; 
            width: 100%; 
        }

        /* Denah Container - Lebih Elegan */
        .denah-container { 
            width: 450px; 
            height: 700px; 
            background: rgba(255,255,255,0.9) url('{{ asset("image/floorplan.jpg") }}') no-repeat center; 
            background-size: contain; 
            border: 2px solid rgba(255,255,255,0.2); 
            border-radius: 25px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4); 
        }

        /* Panel Opsi - Konsep Glassmorphism */
        .panel-opsi { 
            flex: 1;
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 35px; 
            border-radius: 30px; 
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: sticky; 
            top: 40px; 
        }

        h3 { margin-bottom: 10px; font-weight: 800; color: #fff; }
        .pilihan-meja-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 25px 0; }

        /* Style Card Meja Modern */
        .card-meja { 
            padding: 20px; 
            background: rgba(255, 255, 255, 0.05); 
            border: 1.5px solid rgba(255, 255, 255, 0.15); 
            border-radius: 18px; 
            text-align: center; 
            cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            color: white; 
        }

        .card-meja:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #fff;
            transform: translateY(-5px);
        }

        .card-meja.selected { 
            background: #ff9f43 !important; 
            color: white !important; 
            border-color: #ff9f43 !important; 
            box-shadow: 0 10px 20px rgba(255, 159, 67, 0.4); 
        }

        /* State Locks (Logika Asli) */
        .card-meja.occupied-lock { 
            cursor: not-allowed !important; 
            background: rgba(255, 71, 87, 0.1) !important; 
            border-color: rgba(255, 71, 87, 0.3) !important; 
            color: #ff4757 !important; 
            opacity: 0.6; 
        }

        .card-meja.available-lock { 
            cursor: not-allowed !important; 
            background: rgba(255, 255, 255, 0.05) !important; 
            border-color: rgba(255, 255, 255, 0.1) !important; 
            color: rgba(255, 255, 255, 0.3) !important; 
            opacity: 0.4; 
        }

        .status-box { 
            padding: 25px; 
            background: rgba(255, 255, 255, 0.05); 
            border-radius: 20px; 
            margin-bottom: 25px; 
            text-align: center; 
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-konfirmasi { 
            width: 100%; 
            padding: 20px; 
            background: #fff; 
            color: #1a1a1a; 
            border: none; 
            border-radius: 18px; 
            font-size: 16px; 
            font-weight: 800; 
            cursor: pointer; 
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-konfirmasi:hover:not(:disabled) {
            background: #ff9f43;
            color: white;
            box-shadow: 0 10px 25px rgba(255, 159, 67, 0.4);
        }

        .btn-konfirmasi:disabled { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.3); cursor: not-allowed; }

        /* Mobile View */
        @media (max-width: 900px) {
            .main-wrapper { flex-direction: column; align-items: center; }
            .denah-container { width: 100%; height: 400px; }
            .panel-opsi { width: 100%; position: relative; top: 0; }
        }
    </style>
</head>
<body>

    <h2>{{ request('mode') == 'reorder' ? 'Tambah Pesanan' : 'Reservasi Meja Baru' }}</h2>

    <div class="main-wrapper">
        <div class="denah-container"></div>

        <div class="panel-opsi">
            <h3>Pilih Meja</h3>
            <p style="color: rgba(255,255,255,0.6); font-size: 14px; margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i> 
                {{ request('mode') == 'reorder' ? 'Pilih meja yang Anda tempati sekarang.' : 'Pilih meja yang tersedia untuk reservasi.' }}
            </p>

            <div class="pilihan-meja-grid">
                @foreach($tables as $t)
                    @php
                        $mode = request('mode');
                        $isOccupied = ($t->status == 'occupied');
                        if ($mode == 'reorder') {
                            $canSelect = $isOccupied;
                            $statusClass = $isOccupied ? '' : 'available-lock';
                        } else {
                            $canSelect = !$isOccupied;
                            $statusClass = $isOccupied ? 'occupied-lock' : '';
                        }
                    @endphp

                    <div class="card-meja {{ $statusClass }}"
                        data-id="{{ $t->id }}"
                        data-number="{{ $t->table_number }}"
                        data-can-click="{{ $canSelect ? 'true' : 'false' }}">

                        <div style="font-size: 22px; font-weight: 800;">{{ $t->table_number }}</div>
                        <div style="font-size: 10px; font-weight: 600; margin-top: 5px; letter-spacing: 1px;">
                            {{ $isOccupied ? 'TERISI' : 'KOSONG' }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="status-box">
                <span style="font-size: 12px; opacity: 0.6; text-transform: uppercase;">Meja Dipilih</span><br>
                <strong id="meja-label" style="font-size: 32px; color: #ff9f43;">-</strong>
            </div>

            <form action="{{ route('confirm.table') }}" method="POST">
                @csrf
                <input type="hidden" name="table_id" id="hidden-id">
                <input type="hidden" name="mode" value="{{ request('mode') }}">
                <button type="submit" id="btn-submit" class="btn-konfirmasi" disabled>
                    Konfirmasi Meja
                </button>
            </form>
        </div>
    </div>

    <script>
        const label = document.getElementById('meja-label');
        const hiddenInput = document.getElementById('hidden-id');
        const btnSubmit = document.getElementById('btn-submit');
        let selectedMeja = null;

        function attachClickEvents() {
            document.querySelectorAll('.card-meja').forEach(card => {
                if (card.dataset.canClick === 'true') {
                    card.onclick = function() {
                        if (selectedMeja) selectedMeja.classList.remove('selected');
                        card.classList.add('selected');
                        selectedMeja = card;
                        label.innerText = card.dataset.number;
                        hiddenInput.value = card.dataset.id;
                        btnSubmit.disabled = false;
                    };
                }
            });
        }

        setInterval(function() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newGrid = doc.querySelector('.pilihan-meja-grid').innerHTML;
                    let oldGrid = document.querySelector('.pilihan-meja-grid');

                    if (oldGrid.innerHTML !== newGrid) {
                        oldGrid.innerHTML = newGrid;
                        attachClickEvents();
                        console.log('Denah diperbarui otomatis!');
                    }
                });
        }, 5000);

        attachClickEvents();
    </script>
</body>
</html>