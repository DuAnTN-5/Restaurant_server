<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'table_id', 'reservation_date', 'guest_count', 'status', 'special_requests'
    ];

    // A reservation belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A reservation belongs to a table
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
