<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\TableLayout;

class TableLayoutsTableSeeder extends Seeder
{
    public function run(): void
    {
        $layouts = [
            ['no' => 'L1', 'x' => 215, 'y' => 380], // Tengah atas
            ['no' => 'L2', 'x' => 215, 'y' => 450], // Agak bawah L1
            ['no' => 'L3', 'x' => 215, 'y' => 520], // Agak bawah L2
            ['no' => 'L4', 'x' => 215, 'y' => 590], // Dekat pintu
            ['no' => 'L5', 'x' => 215, 'y' => 660], // Paling bawah tengah
            ['no' => 'S1', 'x' => 380, 'y' => 450], // Kanan atas
            ['no' => 'S2', 'x' => 380, 'y' => 550], // Kanan bawah
        ];

        foreach ($layouts as $item) {
            $table = \App\Models\Table::where('table_number', $item['no'])->first();
            if ($table) {
                \App\Models\TableLayout::updateOrCreate(
                    ['table_id' => $table->id],
                    ['x' => $item['x'], 'y' => $item['y']]
                );
            }
        }
    }
}
