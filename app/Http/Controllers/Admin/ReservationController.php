<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Menampilkan daftar semua pesanan
    public function index()
    {
        // Filter: Sembunyikan yang masih 'pending_payment' (belum bayar)
        // Jadi Admin cuma liat pesanan yang SUDAH bayar ('paid') atau yang sudah di-ACC ('confirmed')
        $reservations = \App\Models\Reservation::where('status', '!=', 'pending_payment')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    // Fungsi Update Status (ACC/Tolak)
    public function updateStatus(Request $request, $id)
    {
        $res = Reservation::findOrFail($id);
        $res->status = $request->status; // Menerima 'confirmed' atau 'rejected' dari form
        $res->save();

        return back()->with('success', 'Pesanan Meja ' . $res->table_id . ' berhasil di-' . $request->status);
    }

    // Fungsi Lihat Detail Pesanan (Menu apa saja yang dibeli)
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Asumsi: detail item disimpan di kolom 'items' (JSON) atau relasi
        // Kalau lo pake relasi: $reservation->load('orderItems.menu');

        return view('admin.reservations.show', compact('reservation'));
    }
}
