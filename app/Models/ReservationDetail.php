<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationDetail extends Model
{
    use HasFactory;

    protected $fillable = ['reservation_id', 'menu_id', 'quantity', 'price'];

    public function menu()
    {
        // Relasi ke tabel Menu buat ambil Nama Makanan/Minuman
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}