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
        'total',
        'items', // Kalau lo pake detail tabel, field ini sebenernya udah gak perlu
        'order_number',
        'completed_at', // Tambahin ini
        'duration'      // Tambahin ini
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi: reservasi milik satu meja
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class, 'reservation_id');
    }
    /**
     * Relasi: reservasi punya satu pembayaran
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
