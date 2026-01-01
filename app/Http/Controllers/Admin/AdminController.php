<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Table;

class AdminController extends Controller
{
    /**
     * HALAMAN LOGIN ADMIN
     */
    public function login()
    {
        return view('admin.login');
    }

    /**
     * PROSES LOGIN
     */
    public function doLogin(Request $request)
    {
        return redirect('/admin/dashboard');
    }

    /**
     * DASHBOARD ADMIN
     * Menampilkan semua data reservasi
     */
    public function dashboard()
    {
        // Ambil semua data reservasi, urutkan yang terbaru di atas
        $reservations = Reservation::orderBy('created_at', 'desc')->get();

        // Kirim variabel $reservations ke view dashboard
        return view('admin.dashboard', compact('reservations'));
    }

    /**
     * TAMBAHAN: UPDATE STATUS (ACC / TOLAK)
     * Fungsi ini untuk memproses tombol 'Terima' atau 'Tolak'
     */
    public function updateStatus(Request $request, $id)
    {
        $res = Reservation::findOrFail($id);
        $res->status = $request->status; // Mengambil value 'confirmed' atau 'rejected' dari hidden input
        $res->save();

        return back()->with('success', 'Status pesanan meja ' . $res->table_id . ' berhasil diupdate!');
    }

    /**
     * TAMBAHAN: LIHAT DETAIL PESANAN
     * Fungsi ini untuk menampilkan menu apa saja yang dipesan (Nasi Goreng dkk)
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Mengarahkan ke file resources/views/admin/show.blade.php
        return view('admin.show', compact('reservation'));
    }
}
