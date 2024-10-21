<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'feedbacks';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'name',
        'email',
        'phone_number',
        'message',
        'feedback_date',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['feedback_date', 'created_at', 'updated_at', 'deleted_at'];

    // Mối quan hệ Many-to-One với bảng users (một phản hồi có thể được gửi bởi một người dùng đã đăng ký)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mối quan hệ Many-to-One với bảng orders (một phản hồi có thể liên quan đến một đơn hàng cụ thể)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Mối quan hệ Many-to-One với bảng products (một phản hồi có thể liên quan đến một sản phẩm cụ thể)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Kiểm tra trạng thái phản hồi (new, reviewed, resolved)
    public function isNew()
    {
        return $this->status === 'new';
    }

    public function isReviewed()
    {
        return $this->status === 'reviewed';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }
}
