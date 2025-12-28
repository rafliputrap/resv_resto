<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $data = Reservation::with('table')->latest()->get();
        return view('admin.reservations', compact('data'));
    }
}
