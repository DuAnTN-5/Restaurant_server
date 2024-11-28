<?php

namespace App\Models\Api;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $table = 'cart_item';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
}

