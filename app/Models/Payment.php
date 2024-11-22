<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng liên kết
    protected $table = 'payments';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'order_id',
        'table_id',
        'payment_method_id',  // Thay vì lưu chuỗi, sử dụng khóa ngoại cho phương thức thanh toán
        'payment_status',
        'transaction_id',
        'amount',
        'tax_amount',
        'total_amount',
        'provider_response',
        'error_message',
        'payment_date',
        'created_at',
        'updated_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['payment_date', 'created_at', 'updated_at', 'deleted_at'];

    // Mối quan hệ Many-to-One với bảng orders
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Mối quan hệ Many-to-One với bảng coupons
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    // Mối quan hệ Many-to-One với bảng tables
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    // Mối quan hệ Many-to-One với bảng payment_methods
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // Kiểm tra nếu thanh toán qua MoMo
    public function isMoMo()
    {
        return optional($this->paymentMethod)->name === 'MoMo';
    }

    // Kiểm tra nếu thanh toán qua VNPay
    public function isVNPay()
    {
        return optional($this->paymentMethod)->name === 'VNPay';
    }

    // Kiểm tra nếu thanh toán bằng tiền mặt
    public function isCash()
    {
        return optional($this->paymentMethod)->name === 'Cash';
    }

    // Kiểm tra trạng thái thanh toán hoàn thành
    public function isCompleted()
    {
        return $this->payment_status === 'completed';
    }

    // Kiểm tra nếu thanh toán đang chờ xử lý
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    // Kiểm tra nếu thanh toán thất bại
    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    // Tính toán tổng số tiền thanh toán (bao gồm thuế)
    public function calculateTotalAmount()
    {
        $this->total_amount = $this->amount + $this->tax_amount;
        return $this->total_amount;
    }
}
