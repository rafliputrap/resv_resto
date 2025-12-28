<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('admin.tables', compact('tables'));
    }

    public function store(Request $request)
    {
        Table::create([
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'status' => 'available'
        ]);

        return back()->with('success', 'Meja ditambahkan');
    }
}
