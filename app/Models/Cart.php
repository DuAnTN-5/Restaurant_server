<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

     /**
     * Một giỏ hàng thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Một giỏ hàng có thể có nhiều mục giỏ hàng.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
