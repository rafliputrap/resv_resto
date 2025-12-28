<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Reservation;

class UserController extends Controller
{
    /* ===============================
     * 1. HALAMAN AWAL
     * =============================== */
    public function askTable()
    {
        // reset session biar bisa test ulang
        session()->forget(['table_id', 'cart']);

        return view('user.ask-table');
    }

    /* ===============================
     * 2. PILIH MEJA (DENAH)
     * =============================== */
    public function selectTable()
    {
        // AMBIL SEMUA MEJA + POSISI
        $tables = Table::with('layout')->get();

        return view('user.select-table', compact('tables'));
    }

    public function chooseTable(Request $request)
    {
        if (!$request->table_id) {
            return back()->with('error', 'Meja tidak dipilih');
        }

        $table = Table::findOrFail($request->table_id);

        // ❌ TIDAK ADA UPDATE STATUS (MODE TEST)
        // $table->update(['status' => 'occupied']);

        // simpan meja ke session
        session(['table_id' => $table->id]);

        // pindah ke menu
        return redirect('/menu');
    }

    /* ===============================
     * 3. HALAMAN MENU
     * =============================== */
    public function menu()
    {
        $tableId = session('table_id');

        if (!$tableId) {
            return redirect('/select-table');
        }

        $table = Table::findOrFail($tableId);
        $menus = Menu::all();

        return view('user.menu', compact('table', 'menus'));
    }

    /* ===============================
     * 4. PROSES PILIH MENU
     * =============================== */
    public function order(Request $request)
    {
        // ambil menu yg qty > 0
        $cart = array_filter($request->menu ?? [], fn ($q) => $q > 0);

        if (empty($cart)) {
            return back()->with('error', 'Pilih minimal 1 menu');
        }

        session(['cart' => $cart]);

        return redirect('/order');
    }

    public function orderPage()
    {
        $cart = session('cart');

        if (!$cart) {
            return redirect('/menu');
        }

        $menus = Menu::whereIn('id', array_keys($cart))->get();

        $total = 0;
        foreach ($menus as $m) {
            $total += $m->price * $cart[$m->id];
        }

        return view('user.order', compact('menus', 'cart', 'total'));
    }

    /* ===============================
     * 5. PAYMENT
     * =============================== */
    public function payment()
    {
        $cart = session('cart');

        if (!$cart) {
            return redirect('/menu');
        }

        $menus = Menu::whereIn('id', array_keys($cart))->get();

        $total = 0;
        foreach ($menus as $m) {
            $total += $m->price * $cart[$m->id];
        }

        return view('user.payment', compact('menus', 'cart', 'total'));
    }

    /* ===============================
     * 6. SIMPAN (TEST MODE)
     * =============================== */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'total' => 'required|numeric',
        ]);

        Reservation::create([
            'table_id' => session('table_id'),
            'customer_name' => $request->name,
            'phone' => $request->phone,
            'total' => $request->total,
            'status' => 'test',
        ]);

        // reset biar bisa klik denah ulang
        session()->forget(['table_id', 'cart']);

        return redirect('/')->with('success', 'TEST ORDER BERHASIL');
    }
}
