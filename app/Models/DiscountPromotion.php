<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountPromotion extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'discounts_promotions';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'code',
        'type',
        'promotion_type',
        'value',
        'start_date',
        'end_date',
        'usage_limit',
        'minimum_order_value',
        'conditions',
        'status',
        'created_at',
        'updated_at',
    ];

    // Định dạng các trường kiểu ngày
    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

    // Mối quan hệ One-to-Many với bảng orders (một chương trình giảm giá hoặc khuyến mãi có thể áp dụng cho nhiều đơn hàng)
    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_promotion_id');
    }

    // Mối quan hệ One-to-Many với bảng order_items (một chương trình giảm giá có thể áp dụng cho nhiều mục trong đơn hàng)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'discount_promotion_id');
    }

    // Kiểm tra trạng thái giảm giá/khuyến mãi có còn hoạt động hay không
    public function isActive()
    {
        return $this->status === 'active' && now()->between($this->start_date, $this->end_date);
    }

    // Kiểm tra xem chương trình khuyến mãi đã hết hạn chưa
    public function isExpired()
    {
        return now()->greaterThan($this->end_date);
    }

    // Kiểm tra nếu chương trình khuyến mãi có giới hạn số lần sử dụng
    public function hasUsageLimit()
    {
        return $this->usage_limit > 0;
    }
}
