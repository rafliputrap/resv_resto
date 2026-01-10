@extends('admin.admin')

@section('content')
<style>
    #content {
        width: 100%;
        background-color: #f4f7f6;
        padding: 30px;
        min-height: 100vh;
    }

    .table-container {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* Penyesuaian agar sama dengan style User */
    .menu-img-admin {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 10px;
        background: #f9f9f9;
        border: 1px solid #eee;
    }

    .btn-add {
        background: #e67e22;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 10px;
        font-weight: 700;
        transition: 0.3s;
    }

    .btn-add:hover {
        background: #d35400;
        color: white;
        transform: translateY(-2px);
    }

    /* Style Tombol Aksi agar Jelas */
    .btn-action-edit {
        background-color: #f39c12;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
    }

    .btn-action-delete {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
    }
</style>

<div id="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen Menu</h4>
        </div>
        <button class="btn btn-add shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMenu">
            <i class="fas fa-plus me-2"></i> Tambah Menu
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small">GAMBAR</th>
                        <th class="text-muted small">NAMA MENU</th>
                        <th class="text-muted small">KATEGORI</th>
                        <th class="text-muted small">HARGA</th>
                        <th class="text-center text-muted small">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr>
                        <td class="ps-4">
                            {{-- SINKRON DENGAN USER: Menggunakan folder 'image' --}}
                            <img src="{{ asset('image/' . $menu->image) }}" 
                                 class="menu-img-admin" 
                                 onerror="this.src='https://via.placeholder.com/150'">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $menu->name }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 text-uppercase" style="font-size: 11px;">
                                {{ $menu->category }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">Rp{{ number_format($menu->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Edit Jelas --}}
                                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn-action-edit" title="Edit">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>

                                {{-- Tombol Hapus Jelas --}}
                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" onclick="return confirm('Hapus menu ini?')" title="Hapus">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted fst-italic">
                            Belum ada menu. Silakan klik Tambah Menu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH (PASTIKAN INPUT FILE) --}}
<div class="modal fade" id="modalTambahMenu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title fw-bold">TAMBAH MENU</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold">Nama Menu</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="MAKANAN">MAKANAN</option>
                        <option value="MINUMAN">MINUMAN</option>
                        <option value="SNACK">SNACK</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Harga</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Upload Gambar (ke public/image)</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark w-100">SIMPAN MENU</button>
            </div>
        </form>
    </div>
</div>
@endsection