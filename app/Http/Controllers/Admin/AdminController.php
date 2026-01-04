<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * DASHBOARD: Menampilkan Meja yang SEDANG AKTIF (Masih Makan)
     */
    public function dashboard()
    {
        // WAJIB: Pakai with() supaya data menu ikut terbaca di modal detail
        $reservations = Reservation::with(['reservationDetails.menu'])
            ->whereDate('created_at', date('Y-m-d'))
            ->whereIn('status', ['paid', 'confirmed'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik tetap sama
        $totalOmzet = Reservation::whereDate('created_at', date('Y-m-d'))->where('status', 'completed')->sum('total');
        $totalPengunjung = Reservation::whereDate('created_at', date('Y-m-d'))->where('status', 'completed')->count();
        $activeTables = $reservations->unique('table_id')->count();

        return view('admin.dashboard', compact('reservations', 'totalOmzet', 'totalPengunjung', 'activeTables'));
    }

    public function history(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        $date = $request->get('date', date('Y-m-d'));
        $now = Carbon::now();

        $query = Reservation::with(['reservationDetails.menu'])
            ->where('status', 'completed');

        // Logic Filter Gabungan
        if ($filter == 'daily') {
            $query->whereDate('created_at', $date);
        } elseif ($filter == 'weekly') {
            $query->whereBetween('created_at', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')]);
        } elseif ($filter == 'monthly') {
            $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        } elseif ($filter == 'yearly') {
            $query->whereYear('created_at', $now->year);
        }

        $totalOmzet = (clone $query)->sum('total');
        $totalPengunjung = (clone $query)->count();

        $history = $query->latest('completed_at')->get()->map(function ($item) {
            if ($item->created_at && $item->completed_at) {
                $item->duration = Carbon::parse($item->created_at)->diffForHumans(Carbon::parse($item->completed_at), true);
            } else {
                $item->duration = "-";
            }
            return $item;
        });

        return view('admin.history', compact('history', 'date', 'totalOmzet', 'totalPengunjung', 'filter'));
    }

    public function resetTable($table_id)
    {
        // 1. Cari reservasi yang aktif di meja tersebut dan selesaikan
        Reservation::where('table_id', $table_id)
            ->whereIn('status', ['paid', 'confirmed'])
            ->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

        // 2. Reset status fisik meja jadi 'available' agar di denah jadi putih/kosong
        Table::where('id', $table_id)->update(['status' => 'available']);

        return redirect()->route('admin.dashboard')->with('success', 'Meja berhasil diselesaikan!');
    }

    public function destroy($id)
    {
        Reservation::findOrFail($id)->delete(); // Hapus data salah
        return back()->with('success', 'Data dihapus!');
    }
}
