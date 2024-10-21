<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'payments';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'order_id',
        'table_id',
        'payment_method',
        'payment_status',
        'transaction_id',
        'amount',
        'tax_amount',
        'total_amount',
        'provider_response',  // Thêm trường phản hồi từ nhà cung cấp
        'error_message',  // Thêm trường lưu thông báo lỗi khi thanh toán thất bại
        'payment_date',
        'created_at',
        'updated_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['payment_date', 'created_at', 'updated_at'];

    // Mối quan hệ Many-to-One với bảng orders (một khoản thanh toán liên quan đến một đơn hàng)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Mối quan hệ Many-to-One với bảng tables (một khoản thanh toán có thể liên quan đến một bàn ăn)
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    // Kiểm tra nếu thanh toán là qua MoMo
    public function isMoMo()
    {
        return $this->payment_method === 'momo';
    }

    // Kiểm tra nếu thanh toán là qua VNPay
    public function isVNPay()
    {
        return $this->payment_method === 'vnpay';
    }

    // Kiểm tra nếu thanh toán là bằng tiền mặt
    public function isCash()
    {
        return $this->payment_method === 'cash';
    }

    // Kiểm tra trạng thái thanh toán có hoàn thành hay chưa
    public function isCompleted()
    {
        return $this->payment_status === 'completed';
    }

    // Kiểm tra nếu thanh toán đang chờ xử lý
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    // Kiểm tra nếu thanh toán đã thất bại
    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    // Tính toán tổng số tiền thanh toán (bao gồm thuế)
    public function calculateTotalAmount()
    {
        return $this->amount + $this->tax_amount;
    }
}
