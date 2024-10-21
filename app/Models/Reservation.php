<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    // Các trường có thể điền vào (Mass Assignment)
    protected $fillable = [
        'user_id',
        'staff_id',
        'table_id',
        'reservation_date',
        'guest_count',
        'special_requests',
        'status',
    ];

    // Định dạng cho các trường kiểu dữ liệu
    protected $casts = [
        'reservation_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Một đặt chỗ thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Một đặt chỗ thuộc về một nhân viên quản lý (staff)
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // Một đặt chỗ thuộc về một bàn
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    // Kiểm tra trạng thái đặt chỗ
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    // Kiểm tra nếu đặt chỗ bị hủy
    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    // Kiểm tra nếu đặt chỗ đang chờ xử lý
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
