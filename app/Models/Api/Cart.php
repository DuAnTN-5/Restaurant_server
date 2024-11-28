<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'table_id',
        'date',
        'time',
        'guest_count',
        'notes', 
    ];
}
