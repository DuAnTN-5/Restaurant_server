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
        'parent_id',
        'position',
        'status',
        'meta_keywords',
        'meta_description',
        'created_at',
        'updated_at'
    ];

    // Quan hệ One-to-Many với model Post (một danh mục có nhiều bài viết)
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    // Quan hệ self-referencing: Một danh mục có thể có nhiều danh mục con
    public function children()
    {
        return $this->hasMany(PostCategory::class, 'parent_id');
    }

    // Quan hệ self-referencing: Một danh mục có thể thuộc về một danh mục cha
    public function parent()
    {
        return $this->belongsTo(PostCategory::class, 'parent_id');
    }

    // Kiểm tra xem danh mục có danh mục con hay không
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    // Lấy slug của danh mục để sử dụng cho URL
    public function getSlugAttribute()
    {
        return $this->slug;
    }
}
