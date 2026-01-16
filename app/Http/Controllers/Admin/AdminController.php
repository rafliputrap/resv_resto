<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
// PERBAIKAN: Gunakan alias yang konsisten untuk PDF
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function dashboard()
    {
        $reservations = Reservation::with(['reservationDetails.menu'])
            ->whereDate('created_at', date('Y-m-d'))
            ->whereIn('status', ['paid', 'confirmed'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalOmzet = Reservation::whereDate('created_at', date('Y-m-d'))->where('status', 'completed')->sum('total');
        $totalPengunjung = Reservation::whereDate('created_at', date('Y-m-d'))->where('status', 'completed')->count();
        $activeTables = $reservations->unique('table_id')->count();

        return view('admin.dashboard', compact('reservations', 'totalOmzet', 'totalPengunjung', 'activeTables'));
    }

    public function history(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        $date = $request->get('date', date('Y-m-d'));
        $now = Carbon::now('Asia/Jakarta');

        $query = Reservation::with(['reservationDetails.menu'])
            ->where('status', 'completed');

        // Logic Filter
        if ($filter == 'daily') {
            $query->whereDate('created_at', $date);
        } elseif ($filter == 'weekly') {
            $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
        } elseif ($filter == 'monthly') {
            $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        } elseif ($filter == 'yearly') {
            $query->whereYear('created_at', $now->year);
        }

        $totalOmzet = (clone $query)->sum('total');
        $totalPengunjung = (clone $query)->count();

        $history = $query->latest('completed_at')->get()->map(function ($item) {
            if ($item->created_at && $item->completed_at) {
                $item->duration = Carbon::parse($item->created_at)
                    ->diffForHumans(Carbon::parse($item->completed_at), true);
            } else {
                $item->duration = "-";
            }
            return $item;
        });

        return view('admin.history', compact('history', 'date', 'totalOmzet', 'totalPengunjung', 'filter'));
    }

    public function exportHistory(Request $request)
    {
        // Supaya PDF lancar jaya kalau data banyak
        ini_set('memory_limit', '256M');

        $filter = $request->get('filter', 'daily');
        $date = $request->get('date', date('Y-m-d'));
        $now = Carbon::now('Asia/Jakarta');

        // Query data
        $query = Reservation::with(['reservationDetails.menu'])
            ->where('status', 'completed');

        if ($filter == 'daily') {
            $query->whereDate('created_at', $date);
        } elseif ($filter == 'weekly') {
            $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
        } elseif ($filter == 'monthly') {
            $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        } elseif ($filter == 'yearly') {
            $query->whereYear('created_at', $now->year);
        }

        $data = $query->latest('completed_at')->get();

        // Kalau data kosong, balik ke halaman sebelumnya kasih tau admin
        if ($data->isEmpty()) {
            return back()->with('error', 'Data kosong, tidak ada yang bisa di-export!');
        }

        // Load View PDF
        $pdf = Pdf::loadView('admin.export_pdf', compact('data', 'filter', 'date'));

        // Langsung tembak download
        return $pdf->setPaper('a4', 'landscape')->download("Laporan_Hafa_{$date}.pdf");
    }

    public function resetTable($table_id)
    {
        Reservation::where('table_id', $table_id)
            ->whereIn('status', ['paid', 'confirmed'])
            ->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

        Table::where('id', $table_id)->update(['status' => 'available']);
        return redirect()->route('admin.dashboard')->with('success', 'Meja berhasil diselesaikan!');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        try {
            $reservation->reservationDetails()->delete();
            $reservation->delete();
            return back()->with('success', 'Data transaksi berhasil dihapus permanen!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
