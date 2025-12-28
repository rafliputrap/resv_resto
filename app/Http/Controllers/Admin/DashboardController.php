<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;

class DashboardController extends Controller
{
    public function index() {
        return view('admin.dashboard', [
            'totalReservasi' => Reservation::count(),
            'totalMeja' => Table::count()
        ]);
    }
}
