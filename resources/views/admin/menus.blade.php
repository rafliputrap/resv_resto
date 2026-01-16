@extends('admin.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

<style>
    #content { 
        width: 100%; 
        background-color: #f8fafc; 
        padding: 40px; 
        min-height: 100vh; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .page-title { font-weight: 800; color: #1e293b; letter-spacing: -1px; }
    .table-container { 
        background: #ffffff; 
        border-radius: 24px; 
        overflow: hidden; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        border: 1px solid #edf2f7;
    }
    .table thead th {
        background-color: #fcfcfd;
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #94a3b8;
        padding: 20px;
    }
    .menu-img-admin { width: 60px; height: 60px; object-fit: cover; border-radius: 14px; }
    .badge-makanan { background: #dcfce7; color: #166534; }
    .badge-minuman { background: #dbeafe; color: #1e40af; }
    .btn-add { 
        background: #1e293b; color: white; padding: 12px 24px; 
        border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-block;
    }
    .btn-action { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: none; }
    .btn-edit { background: #fef3c7; color: #d97706; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
</style>

<div id="content">
    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", showConfirmButton: false, timer: 2000 });
        </script>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-1">Menu Management</h2>
            <p class="text-muted small mb-0">Total {{ $menus->count() }} item tersedia</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-add">
            <i class="fas fa-plus me-2"></i> Tambah Menu
        </a>
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
                            <span class="badge {{ $menu->category == 'MAKANAN' ? 'badge-makanan' : 'badge-minuman' }} px-3 py-2 rounded-pill small">
                                {{ $menu->category }}
                            </span>
                        </td>
                        <td><span class="fw-bold">{{ $menu->stock }} unit</span></td>
                        <td><span class="fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span></td>
                        <td><small class="text-muted">{{ Str::limit($menu->description, 30) }}</small></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $menu->id }}')"><i class="fas fa-trash"></i></button>
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

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Item?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e293b',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection