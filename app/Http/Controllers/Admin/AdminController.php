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
     * PROSES LOGIN (sementara TANPA AUTH dulu)
     */
    public function doLogin(Request $request)
    {
        // nanti bisa ditambah validasi admin
        return redirect('/admin/dashboard');
    }

    /**
     * DASHBOARD ADMIN
     */
    public function dashboard()
    {
        $totalReservasi = Reservation::count();
        $totalMeja = Table::count();

        return view('admin.dashboard', compact(
            'totalReservasi',
            'totalMeja'
        ));
    }
}
