<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Coupon extends Model
{
    use HasFactory;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'coupons';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'code',
        'discount_type',
        'value',
        'start_date',
        'end_date',
        'usage_limit',
        'minimum_order_value',
        'status',
        'created_at',
        'updated_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

    // Mối quan hệ One-to-Many với bảng orders (một mã giảm giá có thể được sử dụng cho nhiều đơn hàng)
    public function orders()
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }

    // Kiểm tra nếu mã giảm giá đang hoạt động
    public function isActive()
    {
        return $this->status === 'active' && now()->between($this->start_date, $this->end_date);
    }

    // Kiểm tra xem mã giảm giá có hết hạn hay chưa
    public function isExpired()
    {
        return now()->greaterThan($this->end_date);
    }

    // Kiểm tra xem mã giảm giá có giới hạn số lần sử dụng hay không
    public function hasUsageLimit()
    {
        return $this->usage_limit > 0;
    }
}
