<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishReview extends Model
{
    protected $table = 'reviews_dish';

    protected $fillable = ['user_id', 'dish_id', 'rating', 'comment'];

    // Quan hệ với món ăn
    public function dish()
    {
        return $this->belongsTo(DishReview::class);
    }

    // Quan hệ với người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
