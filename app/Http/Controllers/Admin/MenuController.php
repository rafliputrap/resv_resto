<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus', compact('menus'));
    }

    // Fungsi untuk menyimpan menu baru
    public function store(Request $request)
    {
        // 1. Tambahin stock dan description di validasi biar aman
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required|numeric', // Tambahin ini
            'description' => 'nullable',    // Tambahin ini
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fileName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image'), $fileName);
        }

        // 2. MASALAH UTAMA: Field stock dan description harus dipanggil di sini bre!
        Menu::create([
            'name'        => $request->name,
            'category'    => $request->category,
            'price'       => $request->price,
            'stock'       => $request->stock,       // INI YANG TADI ILANG
            'description' => $request->description, // INI JUGA TADI ILANG
            'image'       => $fileName,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    // Fungsi untuk hapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus file gambar di folder public/image jika ada
        if ($menu->image) {
            $imagePath = public_path('image/' . $menu->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $menu->delete();
        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }

    // Tambahkan ini jika Anda ingin fitur Edit lewat halaman terpisah nantinya
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menu_edit', compact('menu')); // Buat file ini jika perlu
    }

    public function create()
    {
        return view('admin.create');
    }

    public function update(Request $request, $id)
    {
        // 1. Cari data menu berdasarkan ID
        $menu = Menu::findOrFail($id);

        // 2. Validasi input
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Gambar opsional pas update
        ]);

        // 3. Ambil semua input kecuali image dulu
        $data = $request->except('image');

        // 4. Cek kalau ada file gambar baru yang diupload
        if ($request->hasFile('image')) {

            // --- LOGIKA HAPUS GAMBAR LAMA (BIAR GAK BENGKAK) ---
            if ($menu->image) {
                $oldImagePath = public_path('image/' . $menu->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            // --------------------------------------------------

            // Simpan gambar baru
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image'), $imageName);

            // Masukkan nama file baru ke array data yang akan diupdate
            $data['image'] = $imageName;
        }

        // 5. Update data ke database
        $menu->update($data);

        // 6. Redirect balik ke halaman manajemen menu
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diupdate dan file lama dibersihkan!');
    }
}
