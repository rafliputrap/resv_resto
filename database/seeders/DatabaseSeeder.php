<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* ======================
         * ADMIN DEFAULT
         * ====================== */
        Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123')
            ]
        );

        /* ======================
         * MEJA
         * ====================== */
        Table::updateOrCreate(
            ['qr_token' => 'A1QR'],
            [
                'table_number' => '1',
                'capacity' => 4,
                'status' => 'available'
            ]
        );

        /* ======================
         * MENU
         * ====================== */
        Menu::updateOrCreate(
            ['name' => 'Nasi Goreng'],
            ['price' => 20000, 'description' => 'Enak & gurih']
        );

        Menu::updateOrCreate(
            ['name' => 'Mie Ayam'],
            ['price' => 15000, 'description' => 'Pedas manis']
        );

        Menu::updateOrCreate(
            ['name' => 'Es Teh'],
            ['price' => 5000, 'description' => 'Segar']
        );
    }
}
