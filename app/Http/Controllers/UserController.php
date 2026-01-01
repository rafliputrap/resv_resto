<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Reservation;
use Midtrans\Config;
use Midtrans\Snap;

class UserController extends Controller
{
    // 1. Halaman Awal & Pilih Meja
    public function askTable()
    {
        session()->forget(['table_id', 'cart']);
        return view('user.ask-table');
    }

    public function selectTable()
    {
        $tables = Table::with('layout')->get();
        return view('user.select-table', compact('tables'));
    }

    public function chooseTable(Request $request)
    {
        if (!$request->table_id) {
            return back()->with('error', 'Meja tidak dipilih');
        }

        $table = Table::findOrFail($request->table_id);
        session()->forget('cart');
        session(['table_id' => $table->id]);

        return redirect()->route('user.menu');
    }

    // 2. Halaman Menu & Keranjang (AJAX)
    public function menu()
    {
        $tableId = session('table_id');
        if (!$tableId) return redirect()->route('select.table');

        $table = Table::find($tableId);
        if (!$table) {
            session()->forget('table_id');
            return redirect()->route('select.table');
        }

        $menus = Menu::all()->groupBy('category');
        $totalHarga = 0;
        $cart = session('cart', []);

        foreach ($cart as $details) {
            $totalHarga += ($details['price'] ?? 0) * ($details['quantity'] ?? 0);
        }

        return view('user.menu', compact('table', 'menus', 'totalHarga'));
    }

    public function addToCart(Request $request)
    {
        $menu = Menu::find($request->id);
        if (!$menu) return response()->json(['status' => 'error'], 404);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
        } else {
            $cart[$request->id] = [
                "name" => $menu->name,
                "quantity" => 1,
                "price" => $menu->price,
                "image" => $menu->image
            ];
        }
        session()->put('cart', $cart);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'status' => 'success',
            'total_harga' => number_format($total, 0, ',', '.'),
            'cart_count' => count($cart)
        ]);
    }

    // 3. Checkout & Simpan Pesanan (Logika Order Number Baru)
    public function orderPage()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('user.menu');

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('user.order', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'total' => 'required|numeric',
            'table_id' => 'required'
        ]);

        // GENERATE ORDER NUMBER UNIK: JamMenit-AngkaAcak
        $uniqueOrderCode = date('Hi') . '-' . rand(100, 999);

        $reservation = Reservation::create([
            'order_number'  => $uniqueOrderCode, // Simpan kode unik
            'table_id'      => $request->table_id,
            'customer_name' => $request->name,
            'phone'         => $request->phone,
            'total'         => $request->total,
            'status'        => 'pending_payment',
            'items'         => json_encode(session('cart', [])),
        ]);

        return redirect()->route('user.payment', ['id' => $reservation->id]);
    }

    // 4. Integrasi Midtrans
    public function payment($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                // Gunakan order_number di Midtrans agar sinkron dengan database
                'order_id' => $reservation->order_number . '-' . rand(1, 99),
                'gross_amount' => (int)$reservation->total,
            ],
            'customer_details' => [
                'first_name' => $reservation->customer_name,
                'phone' => $reservation->phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('user.payment', compact('reservation', 'snapToken'));
        } catch (\Exception $e) {
            return "Gagal membuat token Midtrans: " . $e->getMessage();
        }
    }

    // 5. Halaman Sukses & Finish
    public function paymentSuccess($id)
    {
        $res = Reservation::findOrFail($id);

        // Update status jadi paid
        $res->update(['status' => 'paid']);

        // BERSIHKAN SESSION: User dianggap selesai dan harus scan/input ulang jika mau pesan lagi
        session()->forget(['cart', 'table_id']);
        session()->flush();

        return view('user.thanks', compact('res'));
    }
}
