<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\ReservationDetail; 
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // 1. Pilih Meja
    public function askTable() { return view('user.ask-table'); }

    public function selectTable()
    {
        $tables = Table::with('layout')->get();
        return view('user.select-table', compact('tables'));
    }

    public function chooseTable(Request $request)
    {
        $table = Table::findOrFail($request->table_id);
        $mode = $request->input('mode');

        if ($mode == 'reorder') {
            if ($table->status !== 'occupied') return back()->with('error', 'Pilih meja Anda.');
        } else {
            if ($table->status !== 'available') return back()->with('error', 'Meja terisi.');
        }

        session(['table_id' => $table->id]);
        return redirect()->route('user.menu');
    }

    // Fungsi Pesanan Baru (Fix Error 500)
    public function startNewSession()
    {
        session()->forget(['cart', 'reservation_id', 'table_id', 'customer_name']);
        return redirect()->route('select.table');
    }

    // 2. Menu & Keranjang
    public function menu()
    {
        $tableId = session('table_id');
        if (!$tableId) return redirect()->route('select.table');
        $table = Table::find($tableId);
        $menus = Menu::all()->groupBy('category');
        $cart = session('cart', []);
        $totalHarga = 0;
        foreach ($cart as $item) { $totalHarga += $item['price'] * $item['quantity']; }
        return view('user.menu', compact('table', 'menus', 'totalHarga'));
    }

    public function addToCart(Request $request)
    {
        $menu = Menu::find($request->id);
        if (!$menu) return response()->json(['status' => 'error'], 404);
        $cart = session()->get('cart', []);
        if (isset($cart[$request->id])) { $cart[$request->id]['quantity']++; } 
        else {
            $cart[$request->id] = ["name" => $menu->name, "quantity" => 1, "price" => $menu->price, "image" => $menu->image];
        }
        session()->put('cart', $cart);
        $total = 0;
        foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }
        return response()->json(['status' => 'success', 'total_harga' => number_format($total, 0, ',', '.'), 'cart_count' => count($cart)]);
    }

    // 3. HALAMAN CHECKOUT (Fix Error orderPage does not exist)
    public function orderPage()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('user.menu');
        $total = 0;
        foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }
        return view('user.order', compact('cart', 'total'));
    }

    // 4. Simpan Pesanan (Fix Tabel Rincian Kosong)
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'phone' => 'required', 'total' => 'required|numeric', 'table_id' => 'required']);
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('user.menu');

        $reservation = Reservation::create([
            'order_number'  => 'RESO-' . date('Hi') . '-' . rand(100, 999),
            'table_id'      => $request->table_id,
            'customer_name' => $request->name,
            'phone'         => $request->phone,
            'total'         => $request->total,
            'status'        => 'pending_payment',
        ]);

        // Simpan setiap item keranjang ke database
        foreach ($cart as $menuId => $item) {
            ReservationDetail::create([
                'reservation_id' => $reservation->id,
                'menu_id'        => $menuId,
                'quantity'       => $item['quantity'],
                'price'          => $item['price'],
            ]);
        }
        return redirect()->route('user.payment', ['id' => $reservation->id]);
    }

    // 5. Pembayaran Midtrans
    public function payment($id)
    {
        $reservation = Reservation::findOrFail($id);
        Config::$serverKey = 'Mid-server-Qek_Hvs9xu-vTUMbdA2DEoM3';
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $reservation->order_number . '-' . time(), // Fix VA Not Found
                'gross_amount' => (int)$reservation->total,
            ],
            'customer_details' => ['first_name' => $reservation->customer_name, 'phone' => $reservation->phone],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('user.payment', compact('reservation', 'snapToken'));
        } catch (\Exception $e) { return "Gagal: " . $e->getMessage(); }
    }

    public function paymentSuccess($id)
    {
        $res = Reservation::findOrFail($id);
        $res->update(['status' => 'paid']);
        $table = Table::find($res->table_id);
        if ($table) { $table->update(['status' => 'occupied']); }
        session()->forget(['cart', 'table_id']);
        return view('user.thanks', compact('res'));
    }
}