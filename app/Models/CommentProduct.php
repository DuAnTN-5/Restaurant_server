<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentProduct extends Model
{
    protected $table = 'comment_product';
    protected $fillable = ['product_id', 'user_id', 'parent_id', 'content'];

    // Accessor để tách ngày
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d'); // Ví dụ: 2024-11-01
    }

    // Accessor để tách giờ
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i:s'); // Ví dụ: 10:00:00
    }

    // Thêm các thuộc tính này vào JSON trả về
    protected $appends = ['formatted_date', 'formatted_time'];

    // Quan hệ với bình luận con
    public function children()
    {
        return $this->hasMany(CommentProduct::class, 'parent_id');
    }

    // Quan hệ với bình luận cha
    public function parent()
    {
        return $this->belongsTo(CommentProduct::class, 'parent_id');
    }
}
