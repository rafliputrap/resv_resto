<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'table_id',
        'customer_name',
        'phone',
        'reservation_time',
        'status',
        'total'
    ];

    /**
     * Relasi: reservasi milik satu meja
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Relasi: reservasi punya satu pembayaran
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
