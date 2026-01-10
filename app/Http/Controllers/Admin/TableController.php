<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('admin.tables', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:tables,table_number',
            'capacity' => 'required|integer|min:1'
        ]);

        Table::create([
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'status' => 'available'
        ]);

        return back()->with('success', 'Meja berhasil ditambahkan!');
    }

    // FUNGSI UPDATE STATUS (Selesaikan Meja)
    public function updateStatus(Request $request, $id)
    {
        // 1. Ambil data meja
        $table = Table::findOrFail($id);

        // 2. Cari transaksi terakhir yang masih aktif (PAID) di meja tersebut
        // Kita harus ubah statusnya jadi 'completed' agar masuk hitungan omzet/rekap
        $reservation = \App\Models\Reservation::where('table_id', $id)
            ->where('status', 'paid') // Mencari yang sudah bayar tapi belum selesai
            ->latest()
            ->first();

        if ($reservation) {
            $reservation->update([
                'status' => 'completed' // Status 'completed' inilah yang biasanya ditarik ke laporan omzet
            ]);
        }

        // 3. Meja jadi tersedia kembali di database
        $table->update([
            'status' => 'available'
        ]);

        // 4. Redirect sesuai siapa yang akses
        if ($request->is('admin/*')) {
            return back()->with('success', 'Meja ' . $table->table_number . ' telah diselesaikan dan masuk laporan.');
        }

        // Jika diklik oleh Pelanggan di halaman sukses
        return redirect('/')->with('success', 'Terima kasih! Pesanan Anda telah selesai.');
    }
    
    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        return back()->with('success', 'Meja berhasil dihapus!');
    }
}
