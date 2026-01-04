<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reservasi Meja Cafe</title>
    <style>
        body { margin: 0; padding: 20px; font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; flex-direction: column; align-items: center; }
        h2 { margin-bottom: 25px; color: #333; }
        .main-wrapper { display: flex; flex-direction: row; justify-content: center; align-items: flex-start; gap: 40px; max-width: 1200px; width: 100%; }
        
        /* Denah Container */
        .denah-container { width: 450px; height: 800px; background: white url('{{ asset("image/floorplan.jpg") }}') no-repeat center; background-size: contain; border: 5px solid #2c3e50; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        
        /* Panel Opsi */
        .panel-opsi { width: 380px; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08); position: sticky; top: 20px; }
        h3 { margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; color: #2c3e50; }
        .pilihan-meja-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 25px 0; }

        /* Style Meja */
        .card-meja { padding: 20px 10px; background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 12px; text-align: center; font-weight: bold; cursor: pointer; transition: all 0.2s ease; color: #495057; }
        
        /* Meja Dipilih */
        .card-meja.selected { background: #28a745 !important; color: white !important; border-color: #218838 !important; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3); }

        /* Mode: Reservasi Baru (Meja Terisi jadi Abu-abu/Lock) */
        .card-meja.occupied-lock { cursor: not-allowed !important; background-color: #ffebee !important; border-color: #ffcdd2 !important; color: #b71c1c !important; pointer-events: none; opacity: 0.7; }

        /* Mode: Reorder (Meja Kosong jadi Abu-abu/Lock) */
        .card-meja.available-lock { cursor: not-allowed !important; background-color: #f1f3f5 !important; border-color: #dee2e6 !important; color: #adb5bd !important; pointer-events: none; opacity: 0.6; }

        .status-box { padding: 20px; background: #e9ecef; border-radius: 10px; margin-bottom: 25px; text-align: center; }
        .btn-konfirmasi { width: 100%; padding: 18px; background: #2c3e50; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-konfirmasi:disabled { background: #bdc3c7; cursor: not-allowed; }
    </style>
</head>
<body>

    <h2>{{ request('mode') == 'reorder' ? 'TAMBAH PESANAN (PILIH MEJA ANDA)' : 'RESERVASI MEJA BARU' }}</h2>

    <div class="main-wrapper">
        <div class="denah-container"></div>

        <div class="panel-opsi">
            <h3>Detail Pesanan</h3>
            <p style="color: #7f8c8d; font-size: 14px;">
                {{ request('mode') == 'reorder' ? 'Pilih meja yang sedang Anda gunakan sekarang:' : 'Klik nomor meja yang ingin Anda pesan:' }}
            </p>

            <div class="pilihan-meja-grid">
                @foreach($tables as $t)
                    @php
                        $mode = request('mode');
                        $isOccupied = ($t->status == 'occupied');
                        
                        // Logika: Siapa yang boleh diklik?
                        if ($mode == 'reorder') {
                            $canSelect = $isOccupied; // Hanya yang terisi bisa diklik
                            $statusClass = $isOccupied ? '' : 'available-lock';
                        } else {
                            $canSelect = !$isOccupied; // Hanya yang kosong bisa diklik
                            $statusClass = $isOccupied ? 'occupied-lock' : '';
                        }
                    @endphp

                    <div class="card-meja {{ $statusClass }}"
                        data-id="{{ $t->id }}"
                        data-number="{{ $t->table_number }}"
                        data-can-click="{{ $canSelect ? 'true' : 'false' }}">

                        <div style="font-size: 20px; font-weight: bold;">{{ $t->table_number }}</div>
                        <div style="font-size: 11px; font-weight: normal; opacity: 0.8;">
                            {{ $isOccupied ? 'TERISI' : 'KOSONG' }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="status-box">
                Meja Dipilih: <br>
                <strong id="meja-label" style="font-size: 28px; color: #28a745;">-</strong>
            </div>

            <form action="{{ route('confirm.table') }}" method="POST">
                @csrf
                <input type="hidden" name="table_id" id="hidden-id">
                <input type="hidden" name="mode" value="{{ request('mode') }}">
                <button type="submit" id="btn-submit" class="btn-konfirmasi" disabled>
                    KONFIRMASI MEJA
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
                // Hanya pasang click kalau data-can-click nya true
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

        // Script Realtime: Update otomatis tanpa refresh
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
                        attachClickEvents(); // Re-bind event click pada element baru
                        console.log('Denah diperbarui otomatis!');
                    }
                });
        }, 5000);

        attachClickEvents(); // Jalankan pas pertama kali load
    </script>
</body>
</html>