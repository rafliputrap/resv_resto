<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Reservation;

class UserController extends Controller
{
    public function indexDefault() {
        $table = Table::where('qr_token','A1QR')->first();
        $menus = Menu::all();
        return view('user.index', compact('table','menus'));
    }

    public function index($token) {
        $table = Table::where('qr_token',$token)->first();
        $menus = Menu::all();
        return view('user.index', compact('table','menus'));
    }

    public function store(Request $request) {
        $request->validate([
            'table_id'=>'required|exists:tables,id',
            'customer_name'=>'required|string|max:100',
            'phone'=>'required|string|max:20',
        ]);

        Reservation::create([
            'table_id'=>$request->table_id,
            'customer_name'=>$request->customer_name,
            'phone'=>$request->phone,
            'reservation_time'=>now(),
            'status'=>'pending',
        ]);

        Table::where('id',$request->table_id)->update(['status'=>'reserved']);

        return redirect()->back()->with('success','Reservasi berhasil!');
    }
}
