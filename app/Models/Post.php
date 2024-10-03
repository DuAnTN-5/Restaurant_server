<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'title', 
        'body', 
        'user_id', 
        'category_id', 
        'status', 
        'position', 
        'image_url' // Thêm 'position' và 'image_url' vào fillable
    ];

    // Thiết lập quan hệ với bảng post_categories
    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    // Thiết lập quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
