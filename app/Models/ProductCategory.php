<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 'position', 'status'
    ];

    // Relationship to the parent category
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    // Relationship to child categories
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    // Relationship to products (one category can have many products)
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
