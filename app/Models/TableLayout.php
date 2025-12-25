<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'x',
        'y'
    ];

    // 🔗 RELASI
    // denah milik satu meja
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
