<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function login()
    {
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        // 1. Validasi input email dan password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Proses autentikasi
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // 3. Paksa redirect ke route name dashboard admin agar tidak stuck
            return redirect()->route('admin.dashboard');
        }

        // 4. Jika gagal, balikkan ke halaman login dengan pesan error
        return back()->with('error', 'Email atau password salah!');
    }

    /**DASHBOARD: Menampilkan Meja yang SEDANG AKTIF (Masih Makan)
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
        // Mengambil filter, default ke 'daily'
        $filter = $request->get('filter', 'daily');

        // Mengambil tanggal, default ke tanggal hari ini (Real-time)
        $date = $request->get('date', date('Y-m-d'));

        // Gunakan timezone Jakarta agar sinkron dengan waktu lokal
        $now = Carbon::now('Asia/Jakarta');

        $query = Reservation::with(['reservationDetails.menu'])
            ->where('status', 'completed');

        // Logic Filter yang sudah diperbaiki
        if ($filter == 'daily') {
            $query->whereDate('created_at', $date);
        } elseif ($filter == 'weekly') {
            // Gunakan copy() agar $now tidak berubah permanen
            $start = $now->copy()->startOfWeek()->format('Y-m-d H:i:s');
            $end = $now->copy()->endOfWeek()->format('Y-m-d H:i:s');
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($filter == 'monthly') {
            $query->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year);
        } elseif ($filter == 'yearly') {
            $query->whereYear('created_at', $now->year);
        }

        // Hitung Ringkasan
        $totalOmzet = (clone $query)->sum('total');
        $totalPengunjung = (clone $query)->count();

        // Ambil Data dengan pengurutan terbaru
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
        // 1. Gunakan Transaction untuk memastikan Detail juga ikut terhapus (Integritas Data)
        // 2. Gunakan find jika ingin handling error manual, atau findOrFail agar otomatis 404
        $reservation = Reservation::findOrFail($id);

        try {
            // Hapus detail reservasi terlebih dahulu jika tidak menggunakan 'onDelete cascade' di database
            $reservation->reservationDetails()->delete();

            // Baru hapus data utama
            $reservation->delete();

            return back()->with('success', 'Data transaksi berhasil dihapus permanen!');
        } catch (\Exception $e) {
            // Jika gagal (misal karena relasi database), sistem tidak akan crash
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Ini kuncinya: Diarahkan balik ke halaman login admin
        return redirect()->route('admin.login');
    }
}
