<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;

    // Khai báo bảng liên kết
    protected $table = 'post_categories';

    // Các cột có thể được phép ghi dữ liệu
    protected $fillable = [
        'name',
        'description',
        'slug',
        'position',
        'status',
        'created_at',
        'updated_at'
    ];

    // Quan hệ với model Post (nhiều bài viết thuộc một danh mục)
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
