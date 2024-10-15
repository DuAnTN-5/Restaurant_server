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
        'slug',
        'description', 
        'summary',
        'price', 
        'category_id', 
        'image_url', 
        'stock_quantity', 
        'discount_price',
        'ingredients', 
        'availability', 
        'position', 
        'status',
        'tags',
        'product_code',
    ];

    // Define relationship to the ProductCategory model
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
