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
}