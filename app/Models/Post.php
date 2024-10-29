<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes cho phép xóa mềm

    // Tên bảng liên kết
    protected $table = 'posts';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'code',
        'title',
        'body',
        'summary',
        'slug',
        'user_id',
        'category_id',
        'status',
        'position',
        'tags',
        'image_url',
        'meta_keywords',
        'meta_description',
    ];

    // Định dạng các trường kiểu ngày
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Thiết lập quan hệ Many-to-One với bảng PostCategory (một bài viết thuộc về một danh mục)
    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    // Thiết lập quan hệ Many-to-One với bảng Users (một bài viết được tạo bởi một người dùng)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Lấy slug để sử dụng cho URL
    // public function getSlugAttribute()
    // {
    //     return $this->slug;
    // }

    // Kiểm tra trạng thái bài viết (ví dụ: published, draft, archived)
    public function isPublished()
    {
        return $this->status === 'published';
    }

    // Trả về danh sách tags dưới dạng mảng
    public function getTagsArray()
    {
        return explode(',', $this->tags);
    }

    // Lấy URL ảnh đại diện nếu có, nếu không thì trả về ảnh mặc định
    // public function getImageUrlAttribute()
    // {
    //     return $this->image_url ? asset($this->image_url) : asset('default-image.png');
    // }
}
