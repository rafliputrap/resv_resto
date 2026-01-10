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
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fileName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            // Simpan langsung ke folder public/image sesuai kebutuhan tampilan user
            $image->move(public_path('image'), $fileName);
        }

        Menu::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'image' => $fileName,
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
}