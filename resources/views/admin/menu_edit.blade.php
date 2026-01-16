<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 40px 20px; color: #2d3436; }
        .card { background: white; width: 100%; max-width: 500px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; }
        .card-header { background: #1a1a1a; color: white; padding: 25px; text-align: center; }
        .card-body { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: #636e72; }
        input, select, textarea { width: 100%; padding: 12px; border: 1.5px solid #eee; border-radius: 8px; box-sizing: border-box; font-size: 14px; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #1a1a1a; }
        .btn-save { width: 100%; padding: 15px; background: #1a1a1a; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 10px; font-size: 16px; }
        .btn-save:hover { background: #333; transform: translateY(-2px); }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; color: #b2bec3; text-decoration: none; font-size: 14px; transition: 0.2s; }
        .btn-cancel:hover { color: #e74c3c; }
        .img-container { background: #f8f9fa; padding: 15px; border-radius: 12px; border: 1px dashed #ddd; text-align: center; }
        .current-img { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; margin-bottom: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h2 style="margin:0; letter-spacing: 1px;">EDIT MENU</h2>
        <p style="margin: 5px 0 0; font-size: 12px; opacity: 0.7;">Perbarui data menu Hafa Warehouse</p>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="name" value="{{ $menu->name }}" placeholder="Contoh: Kopi Susu Aren" required>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Kategori</label>
                    <select name="category">
                        <option value="MAKANAN" {{ $menu->category == 'MAKANAN' ? 'selected' : '' }}>MAKANAN</option>
                        <option value="MINUMAN" {{ $menu->category == 'MINUMAN' ? 'selected' : '' }}>MINUMAN</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Stok Tersedia</label>
                    <input type="number" name="stock" value="{{ $menu->stock }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" value="{{ $menu->price }}" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Ceritakan singkat tentang menu ini...">{{ $menu->description }}</textarea>
            </div>

            <div class="form-group">
                <label>Gambar Menu</label>
                <div class="img-container">
                    @if($menu->image)
                        <img src="{{ asset('image/' . $menu->image) }}" class="current-img" id="preview">
                        <p style="font-size: 11px; color: #636e72; margin-bottom: 10px;">Gambar saat ini</p>
                    @endif
                    <input type="file" name="image" style="border: none; padding: 0;" onchange="previewImage(event)">
                </div>
                <small style="color: #b2bec3; font-size: 11px;">*Kosongkan jika tidak ingin mengganti gambar</small>
            </div>

            <button type="submit" class="btn-save">SIMPAN PERUBAHAN</button>
            <a href="{{ route('admin.menus.index') }}" class="btn-cancel">Kembali ke Daftar Menu</a>
        </form>
    </div>
</div>

<script>
    // Fitur tambahan: preview gambar sebelum upload
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            if(!output) {
                // Jika sebelumnya gak ada gambar, buat tag img baru (opsional)
                location.reload(); 
            }
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>