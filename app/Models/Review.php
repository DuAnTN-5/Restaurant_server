<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'reviews';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'staff_id',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['created_at', 'updated_at'];

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

    // Mối quan hệ Many-to-One với bảng Users (một đánh giá được tạo bởi một người dùng)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mối quan hệ Many-to-One với bảng Products (một đánh giá có thể liên quan đến một sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Mối quan hệ Many-to-One với bảng Orders (một đánh giá có thể liên quan đến một đơn hàng)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Mối quan hệ Many-to-One với bảng Staff (một đánh giá có thể liên quan đến một nhân viên)
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // Kiểm tra xem đánh giá có phải là 5 sao
    public function isFiveStar()
    {
        return $this->rating === 5;
    }

    // Lấy đánh giá ngắn gọn (nếu cần cắt ngắn bình luận dài)
    public function getShortComment($length = 100)
    {
        return strlen($this->comment) > $length ? substr($this->comment, 0, $length) . '...' : $this->comment;
    }
}
