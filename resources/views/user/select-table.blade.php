<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reservasi Meja Cafe</title>
    <style>
        body { 
            margin: 0; padding: 20px; 
            font-family: 'Segoe UI', sans-serif; 
            background: #f4f7f6; 
            display: flex; flex-direction: column; align-items: center;
        }

        h2 { margin-bottom: 25px; color: #333; }

        /* Container utama: Kiri (Denah) & Kanan (Detail) */
        .main-wrapper {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
            gap: 40px;
            max-width: 1200px;
            width: 100%;
        }

        /* --- KIRI: DENAH BERSIH --- */
        .denah-container {
            width: 450px; 
            height: 800px; /* Sesuaikan tinggi portrait lo */
            background: white url('{{ asset("image/floorplan.jpg") }}') no-repeat center;
            background-size: contain;
            border: 5px solid #2c3e50;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* --- KANAN: PANEL DETAIL PESANAN --- */
        .panel-opsi {
            width: 380px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            position: sticky; /* Tetap di layar saat scroll */
            top: 20px;
        }

        h3 { margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; color: #2c3e50; }

        /* Grid Tombol Pilihan Meja */
        .pilihan-meja-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 25px 0;
        }

        .card-meja {
            padding: 20px 10px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #495057;
        }

        .card-meja:hover {
            border-color: #28a745;
            background: #f0fff4;
            color: #28a745;
        }

        /* State saat meja dipilih */
        .card-meja.selected {
            background: #28a745;
            color: white;
            border-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .status-box {
            padding: 20px;
            background: #e9ecef;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
        }

        .btn-konfirmasi {
            width: 100%; padding: 18px;
            background: #2c3e50; color: white;
            border: none; border-radius: 12px;
            font-size: 16px; font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-konfirmasi:disabled { background: #bdc3c7; cursor: not-allowed; }
        .btn-konfirmasi:hover:not(:disabled) { background: #1a252f; }
    </style>
</head>
<body>

    <h2>RESERVASI MEJA</h2>

    <div class="main-wrapper">
        
        <div class="denah-container">
            </div>

        <div class="panel-opsi">
            <h3>Detail Pesanan</h3>
            <p style="color: #7f8c8d; font-size: 14px;">Klik nomor meja di bawah ini sesuai denah:</p>
            
            <div class="pilihan-meja-grid">
                @foreach($tables as $t)
                    <div class="card-meja" 
                         data-id="{{ $t->id }}" 
                         data-number="{{ $t->table_number }}">
                        {{ $t->table_number }}
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

        document.querySelectorAll('.card-meja').forEach(card => {
            card.addEventListener('click', () => {
                // Hapus class selected dari yang lama
                if (selectedMeja) {
                    selectedMeja.classList.remove('selected');
                }

                // Tambah ke yang baru
                card.classList.add('selected');
                selectedMeja = card;

                // Update label & input
                label.innerText = card.dataset.number;
                hiddenInput.value = card.dataset.id;
                
                // Aktifkan tombol konfirmasi
                btnSubmit.disabled = false;
            });
        });
    </script>
</body>
</html>