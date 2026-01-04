<div class="welcome-card">
    <div class="welcome-icon">🍽️</div>
    <h2>Selamat Datang!</h2>
    <p>Silakan pilih opsi di bawah untuk memulai pesanan Anda.</p>
    
    <div class="d-grid gap-3">
        <a href="{{ route('customer.new-session') }}" class="btn-pilih-meja">
            SAYA BELUM PUNYA MEJA
        </a>

        <a href="/select-table?mode=reorder" class="btn-sudah-meja">
            SAYA SUDAH ADA MEJA
        </a>
    </div>

    <div class="footer-note">
        Klik "Sudah Ada Meja" jika ingin menambah pesanan di meja Anda.
    </div>
</div>

<style>
    /* Style tambahan buat tombol opsi kedua */
    .btn-sudah-meja {
        display: block;
        background: #ffffff;
        color: #2c3e50;
        text-decoration: none;
        padding: 15px 25px;
        border-radius: 12px;
        font-weight: bold;
        border: 2px solid #2c3e50;
        transition: all 0.3s ease;
    }
    .btn-sudah-meja:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
    .d-grid { display: grid; }
    .gap-3 { gap: 1rem; }
</style>