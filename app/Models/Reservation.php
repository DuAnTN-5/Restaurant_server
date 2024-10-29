<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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

    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isReserved()
    {
        return $this->status === 'reserved';
    }

    public function isInUse()
    {
        return $this->status === 'in_use';
    }

    // Kiểm tra xem đã quá hạn 3 giờ kể từ khi đặt không
    public function isExpired()
    {
        return $this->created_at->lt(Carbon::now()->subHours(3));
    }

    // Phương thức để cập nhật trạng thái nếu quá hạn 3 giờ
    public function updateStatusIfExpired()
    {
        if ($this->isReserved() && $this->isExpired()) {
            $this->update(['status' => 'available']);
        }

        if ($this->isInUse() && $this->updated_at->lt(Carbon::now()->subHours(3))) {
            $this->update(['status' => 'available']);
        }
    }
}
