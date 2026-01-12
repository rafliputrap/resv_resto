@extends('admin.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    #content { 
        width: 100%; 
        background-color: #f8fafc; 
        padding: 40px; 
        min-height: 100vh; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Page Header */
    .page-title { font-weight: 800; color: #1e293b; letter-spacing: -1px; }
    
    /* Card Container */
    .table-container { 
        background: #ffffff; 
        border-radius: 24px; 
        overflow: hidden; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        border: 1px solid #edf2f7;
    }

    /* Table Styling */
    .table thead th {
        background-color: #fcfcfd;
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #94a3b8;
        padding: 20px;
        border: none;
    }

    .table tbody td { padding: 18px 20px; border-bottom: 1px solid #f1f5f9; }

    /* Menu Image */
    .menu-img-admin { 
        width: 60px; 
        height: 60px; 
        object-fit: cover; 
        border-radius: 14px; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Badges Modern */
    .badge-makanan { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-minuman { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    
    .stock-badge { 
        padding: 5px 10px; 
        border-radius: 8px; 
        font-size: 11px; 
        font-weight: 700; 
    }

    /* Buttons */
    .btn-add { 
        background: #1e293b; 
        color: white; 
        border: none; 
        padding: 12px 24px; 
        border-radius: 12px; 
        font-weight: 700; 
        transition: 0.3s ease; 
    }
    .btn-add:hover { background: #0f172a; transform: translateY(-2px); box-shadow: 0 10px 15px rgba(0,0,0,0.1); }

    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        border: none;
    }
    .btn-edit { background: #fef3c7; color: #d97706; }
    .btn-edit:hover { background: #f59e0b; color: white; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #ef4444; color: white; }

    /* Modal Form */
    .modal-content { border-radius: 24px; border: none; }
    .form-label { font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px; }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 11px;
        border: 1.5px solid #e2e8f0;
        font-size: 14px;
    }
    .form-control:focus { border-color: #1e293b; box-shadow: none; }
</style>

<div id="content">
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                background: '#fff',
                borderRadius: '20px'
            });
        </script>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-1">Menu Management</h2>
            <p class="text-muted small mb-0">Total {{ $menus->count() }} item tersedia di katalog</p>
        </div>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahMenu">
            <i class="fas fa-plus me-2"></i> Tambah Menu
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('image/' . $menu->image) }}" class="menu-img-admin me-3" onerror="this.src='https://via.placeholder.com/150'">
                                <span class="fw-bold text-dark">{{ $menu->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($menu->category == 'MAKANAN')
                                <span class="badge badge-makanan px-3 py-2 rounded-pill small">MAKANAN</span>
                            @else
                                <span class="badge badge-minuman px-3 py-2 rounded-pill small">MINUMAN</span>
                            @endif
                        </td>
                        <td>
                            @if($menu->stock <= 0)
                                <span class="stock-badge bg-danger text-white">Habis</span>
                            @elseif($menu->stock <= 5)
                                <span class="stock-badge bg-warning text-dark">{{ $menu->stock }} (Limit)</span>
                            @else
                                <span class="stock-badge bg-light text-dark border">{{ $menu->stock }} unit</span>
                            @endif
                        </td>
                        <td><span class="fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span></td>
                        <td><small class="text-muted">{{ Str::limit($menu->description, 30) ?: '-' }}</small></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $menu->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $menu->id }}" action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada menu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH (FOKUS 2 KATEGORI) --}}
<div class="modal fade" id="modalTambahMenu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-extrabold mb-0">Buat Menu Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama makanan/minuman" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="MAKANAN">MAKANAN</option>
                            <option value="MINUMAN">MINUMAN</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stock" class="form-control" value="0" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" placeholder="Contoh: 15000" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Detail komposisi/rasa..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Foto Menu</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill fw-bold">TAMBAHKAN KE KATALOG</button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Item?',
            text: "Menu ini akan hilang dari daftar user.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e293b',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection