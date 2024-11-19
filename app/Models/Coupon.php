<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Thêm SoftDeletes nếu sử dụng xóa mềm

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupons';

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

    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

    // Mối quan hệ One-to-Many với bảng payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'coupon_id', 'id');
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
