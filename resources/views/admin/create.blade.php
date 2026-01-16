<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f4f7f6; 
            display: flex; 
            justify-content: center; 
            padding: 40px 20px; 
            color: #2d3436; 
        }
        .card { 
            background: white; 
            width: 100%; 
            max-width: 500px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            overflow: hidden; 
        }
        .card-header { 
            background: #1a1a1a; 
            color: white; 
            padding: 25px; 
            text-align: center; 
        }
        .card-body { 
            padding: 30px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            font-weight: 600; 
            margin-bottom: 8px; 
            font-size: 14px; 
            color: #636e72; 
        }
        input, select, textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1.5px solid #eee; 
            border-radius: 8px; 
            box-sizing: border-box; 
            font-size: 14px; 
            transition: 0.3s; 
        }
        input:focus, select:focus, textarea:focus { 
            outline: none; 
            border-color: #1a1a1a; 
        }
        .btn-save { 
            width: 100%; 
            padding: 15px; 
            background: #1a1a1a; 
            color: white; 
            border: none; 
            border-radius: 10px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px; 
            font-size: 16px; 
        }
        .btn-save:hover { 
            background: #333; 
            transform: translateY(-2px); 
        }
        .btn-cancel { 
            display: block; 
            text-align: center; 
            margin-top: 15px; 
            color: #b2bec3; 
            text-decoration: none; 
            font-size: 14px; 
            transition: 0.2s; 
        }
        .btn-cancel:hover { 
            color: #e74c3c; 
        }
        .img-container { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 12px; 
            border: 1px dashed #ddd; 
            text-align: center; 
        }
        #preview { 
            width: 120px; 
            height: 120px; 
            object-fit: cover; 
            border-radius: 10px; 
            margin-bottom: 10px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            display: none; 
            margin-left: auto; 
            margin-right: auto; 
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h2 style="margin:0; letter-spacing: 1px;">TAMBAH MENU</h2>
        <p style="margin: 5px 0 0; font-size: 12px; opacity: 0.7;">Personal data menu Hafa Warehouse</p>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="name" placeholder="Nama makanan/minuman" required>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Kategori</label>
                    <select name="category" required>
                        <option value="MAKANAN">MAKANAN</option>
                        <option value="MINUMAN">MINUMAN</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Stok Awal</label>
                    <input type="number" name="stock" value="0" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" placeholder="Contoh: 15000" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Detail komposisi/rasa..."></textarea>
            </div>

            <div class="form-group">
                <label>Gambar Menu</label>
                <div class="img-container">
                    <img id="preview">
                    <input type="file" name="image" style="border: none; padding: 0;" onchange="previewImage(event)" required>
                </div>
            </div>

            <button type="submit" class="btn-save">SIMPAN MENU BARU</button>
            <a href="{{ route('admin.menus.index') }}" class="btn-cancel">Kembali ke Daftar Menu</a>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>