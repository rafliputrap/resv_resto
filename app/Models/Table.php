<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['table_number', 'capacity', 'status', 'pos_x', 'pos_y'];

    public function layout()
    {
        return $this->hasOne(TableLayout::class, 'table_id');
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'available' => 'Tersedia',
            'occupied' => 'Digunakan',
            default => 'Tidak Dikenal'
        };
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function getDisplayNumber()
    {
        return 'Meja ' . $this->table_number;
    }
}
