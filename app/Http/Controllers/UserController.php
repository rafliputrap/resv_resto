<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // --- PROSES AWAL & MEJA ---

    public function askTable()
    {
        return view('user.ask-table');
    }

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

    public function startNewSession()
    {
        session()->forget(['cart', 'reservation_id', 'table_id', 'customer_name']);
        return redirect()->route('select.table');
    }

    // --- MENU & KERANJANG ---

    public function menu()
    {
        $tableId = session('table_id');
        if (!$tableId) return redirect()->route('select.table');
        
        $table = Table::find($tableId);
        $menus = Menu::all()->groupBy('category');
        $cart = session('cart', []);
        
        $totalHarga = 0;
        foreach ($cart as $item) {
            $totalHarga += $item['price'] * $item['quantity'];
        }
        return view('user.menu', compact('table', 'menus', 'totalHarga'));
    }

    public function addToCart(Request $request)
    {
        $menu = Menu::find($request->id);
        if (!$menu || $menu->stock <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Menu habis!'], 400);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            if ($cart[$request->id]['quantity'] + 1 > $menu->stock) {
                return response()->json(['status' => 'error', 'message' => 'Stok tidak cukup'], 400);
            }
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
        foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }

        return response()->json([
            'status' => 'success',
            'total_harga' => number_format($total, 0, ',', '.'),
            'cart_count' => count($cart)
        ]);
    }

    // --- CHECKOUT & PEMBAYARAN ---

    public function orderPage()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('user.menu');

        $tableId = session('table_id');
        $table = Table::find($tableId);
        $table_number = $table ? $table->number : session('table_id');

        $total = 0;
        foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }

        return view('user.order', compact('cart', 'total', 'table_number'));
    }

    public function storeAjax(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) return response()->json(['message' => 'Keranjang kosong'], 400);

        return DB::transaction(function () use ($request, $cart) {
            // 1. Simpan Reservasi
            $reservation = Reservation::create([
                'order_number'  => 'RESO-' . date('Hi') . '-' . rand(100, 999),
                'table_id'      => session('table_id'),
                'customer_name' => $request->name,
                'phone'         => $request->phone,
                'total'         => $request->total,
                'status'        => 'pending_payment',
            ]);

            // 2. Simpan Detail & Potong Stok
            foreach ($cart as $menuId => $item) {
                ReservationDetail::create([
                    'reservation_id' => $reservation->id,
                    'menu_id'        => $menuId,
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                ]);
                $menu = Menu::find($menuId);
                if ($menu) { $menu->decrement('stock', $item['quantity']); }
            }

            // 3. Midtrans
            Config::$serverKey = 'Mid-server-Qek_Hvs9xu-vTUMbdA2DEoM3';
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $reservation->order_number . '-' . time(),
                    'gross_amount' => (int)$reservation->total,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'phone' => $request->phone
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            return response()->json([
                'snapToken' => $snapToken,
                'success_url' => route('payment.success', $reservation->id)
            ]);
        });
    }

    public function paymentSuccess($id)
    {
        $res = Reservation::findOrFail($id);
        $res->update(['status' => 'paid']);

        $table = Table::find($res->table_id);
        if ($table) { $table->update(['status' => 'occupied']); }

        session()->forget(['cart']); // table_id jangan dihapus dulu biar bisa dipake di thanks page
        return view('user.thanks', compact('res'));
    }

    public function finishTable($table_id)
    {
        $table = Table::find($table_id);
        if ($table) { $table->update(['status' => 'available']); }
        session()->forget(['cart', 'table_id']);
        return redirect()->route('ask.table');
    }
}