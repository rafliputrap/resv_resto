<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\TableLayout;
use App\Models\Menu;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ADMIN
        Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => Hash::make('admin123')]
        );

        // 2. MEJA + LAYOUT (Sesuai Gambar Denah)
        $tables = [
            // Area LOUNGE (Lingkaran di Kiri)
            ['no' => 'L1', 'cap' => 2, 'x' => 220, 'y' => 380, 'type' => 'bulat'],
            ['no' => 'L2', 'cap' => 2, 'x' => 220, 'y' => 460, 'type' => 'bulat'],
            ['no' => 'L3', 'cap' => 2, 'x' => 220, 'y' => 540, 'type' => 'bulat'],
            ['no' => 'L4', 'cap' => 2, 'x' => 220, 'y' => 620, 'type' => 'bulat'],
            ['no' => 'L5', 'cap' => 2, 'x' => 220, 'y' => 700, 'type' => 'bulat'],

            // Area SERVICE (Kotak di Kanan)
            ['no' => 'S1', 'cap' => 1, 'x' => 550, 'y' => 400, 'type' => 'kotak'],
            ['no' => 'S2', 'cap' => 1, 'x' => 550, 'y' => 480, 'type' => 'kotak'],
        ];

        foreach ($tables as $t) {
            $table = Table::updateOrCreate(
                ['table_number' => $t['no']],
                ['capacity' => $t['cap'], 'status' => 'available']
            );

            TableLayout::updateOrCreate(
                ['table_id' => $table->id],
                ['x' => $t['x'], 'y' => $t['y']]
            );
        }

        // 3. MENU
        $menus = [
            ['name' => 'Nasi Goreng', 'price' => 20000, 'desc' => 'Enak & gurih'],
            ['name' => 'Mie Ayam', 'price' => 15000, 'desc' => 'Pedas manis'],
            ['name' => 'Es Teh', 'price' => 5000, 'desc' => 'Segar'],
        ]; 
        
        {
            $this->call([
                // Seeder meja lo yang lama (kalau ada)
                TableLayoutsTableSeeder::class,
            ]);
        }

        foreach ($menus as $m) {
            Menu::updateOrCreate(['name' => $m['name']], ['price' => $m['price'], 'description' => $m['desc']]);
        }
    }
}
