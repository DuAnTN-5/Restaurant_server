<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes; // Sử dụng tính năng SoftDeletes

    // Các trường có thể điền vào (Mass Assignment)
    protected $fillable = [
        'user_id',
        'staff_id',
        'table_id',
        'discount_promotion_id',
        'coupon_code',
        'order_type',
        'order_date',
        'total_price',
        'payment_status',
        'status',
        'delivery_address',
        'estimated_delivery_time',
        'note',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = [
        'order_date',
        'estimated_delivery_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Một đơn hàng có thể chứa nhiều mục hàng (order items)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Một đơn hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Một đơn hàng được xử lý bởi một nhân viên (staff)
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // Một đơn hàng có thể liên quan đến một bàn cụ thể (table)
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    // Một đơn hàng có thể có mã giảm giá hoặc khuyến mãi (discounts/promotions)
    public function discountPromotion()
    {
        return $this->belongsTo(DiscountPromotion::class, 'discount_promotion_id');
    }

    // Một đơn hàng có thể sử dụng mã giảm giá (coupon)
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code'); // Liên kết mã coupon qua trường coupon_code
    }

    // Một đơn hàng có thể có nhiều thanh toán
    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    // Kiểm tra nếu đơn hàng đã thanh toán đầy đủ
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    // Kiểm tra nếu đơn hàng đang chờ xử lý
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Tính tổng số lượng mục hàng trong đơn hàng
    public function totalItemsCount()
    {
        return $this->orderItems->sum('quantity');
    }

    // Tính tổng tiền của các mục hàng trong đơn hàng
    public function calculateTotalPrice()
    {
        return $this->orderItems->sum('total_price');
    }
}
