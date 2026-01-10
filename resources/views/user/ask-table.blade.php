<div class="welcome-container">
    <h2 class="welcome-title">Selamat Datang! / Welcome!</h2>
    
    <div class="options-grid">
        <a href="/select-table?mode=reorder" class="btn-option btn-orange">
            <div class="text-main">Saya Sudah Ada Meja</div>
        </a>

        <a href="{{ route('customer.new-session') }}" class="btn-option btn-blue">
            <div class="text-main">Saya Belum Ada Meja Ingin Pesan Tempat</div>
        </a>
    </div>

    <div class="footer-note">
        Klik "Sudah Ada Meja" jika ingin menambah pesanan di meja Anda.
    </div>
</div>

<style>
    body {
        background-color: #e0ddd5; /* Warna background cream seperti di gambar */
        font-family: 'Arial', sans-serif;
    }

    .welcome-container {
        text-align: center;
        padding: 50px 20px;
        max-width: 900px;
        margin: 0 auto;
    }

    .welcome-title {
        color: #5d5246;
        margin-bottom: 30px;
        font-weight: normal;
    }

    /* Grid Layout agar tombol bersebelahan */
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Styling Dasar Tombol */
    .btn-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        padding: 40px 20px;
        border-radius: 15px;
        color: white;
        transition: transform 0.2s, box-shadow 0.2s;
        min-height: 200px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        color: white;
    }

    /* Warna Gradasi Oranye */
    .btn-orange {
        background: linear-gradient(135deg, #e69138 0%, #d17a21 100%);
    }

    /* Warna Gradasi Biru */
    .btn-blue {
        background: linear-gradient(135deg, #76b5d9 0%, #5a99c2 100%);
    }

    .icon-wrapper {
        margin-bottom: 15px;
    }

    /* Filter putih untuk icon agar sesuai gambar */
    .icon-wrapper img {
        filter: brightness(0) invert(1);
    }

    .text-main {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 5px;
        line-height: 1.2;
    }

    .text-sub {
        font-size: 0.85rem;
        opacity: 0.9;
        font-style: italic;
    }

    .footer-note {
        margin-top: 20px;
        color: #777;
        font-size: 0.9rem;
    }

    /* Responsive untuk HP agar tombol tumpuk vertikal */
    @media (max-width: 600px) {
        .options-grid {
            grid-template-columns: 1fr;
        }
    }
</style>