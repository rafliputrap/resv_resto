<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan yang sudah bayar atau diproses.
     */
    public function index()
    {
        // Mengambil data reservasi yang statusnya sudah PAID
        // with(['reservationDetails.menu']) memastikan rincian makanan ikut terbawa
        $reservations = \App\Models\Reservation::with(['reservationDetails.menu'])
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        // Data statistik untuk card di dashboard
        $totalOmzet = \App\Models\Reservation::where('status', 'paid')->sum('total');
        $totalPengunjung = \App\Models\Reservation::where('status', 'paid')->count();
        $activeTables = \App\Models\Table::where('status', 'occupied')->count();

        return view('admin.index', compact('reservations', 'totalOmzet', 'totalPengunjung', 'activeTables'));
    }

    /**
     * Fungsi Update Status (ACC / Tolak Pesanan)
     */
    public function updateStatus(Request $request, $id)
    {
        $res = Reservation::findOrFail($id);

        // Update status berdasarkan input (confirmed/rejected)
        $res->status = $request->status;
        $res->save();

        // Jika ditolak, lo bisa tambahin logic buat balikin status meja jadi 'available' di sini
        if ($request->status == 'rejected') {
            $table = \App\Models\Table::find($res->table_id);
            if ($table) {
                $table->update(['status' => 'available']);
            }
        }

        return back()->with('success', 'Pesanan Meja ' . $res->table_id . ' berhasil di-' . $request->status);
    }

    /**
     * Fungsi Lihat Detail Pesanan (Untuk halaman khusus detail)
     */
    public function show($id)
    {
        // Load relasi agar data rincian tersedia di halaman show
        $reservation = Reservation::with(['reservationDetails.menu'])->findOrFail($id);

        return view('admin.reservations.show', compact('reservation'));
    }
}
