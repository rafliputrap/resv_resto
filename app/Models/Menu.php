<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'image',
        'stock'
    ];

    public function getFormattedPrice()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function isAvailable()
    {
        return $this->stock > 0;
    }

    public function getStockStatus()
    {
        if ($this->stock <= 0) {
            return 'Habis';
        } elseif ($this->stock < 10) {
            return 'Terbatas';
        }
        return 'Tersedia';
    }
}

