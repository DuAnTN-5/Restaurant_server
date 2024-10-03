<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'number', 'seats', 'status', 'location'
    ];

    // A table can have many reservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }
}

