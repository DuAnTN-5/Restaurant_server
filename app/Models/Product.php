<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define fillable fields to allow mass assignment
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'category_id', 
        'type', 
        'image_url', 
        'stock_quantity', 
        'discount_price', 
        'availability', 
        'position', 
        'status',
    ];

    // Define relationship to the ProductCategory model
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
