<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    // kolom yang boleh diisi
    protected $fillable = [
        'table_number',
        'capacity',
        'status',
        'qr_token'
    ];

    // 🔗 RELASI
    // 1 meja punya banyak reservasi
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // 1 meja punya 1 posisi denah
    public function layout()
    {
        return $this->hasOne(TableLayout::class);
    }
}
