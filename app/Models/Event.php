<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'events';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'name',
        'description',
        'event_date',
        'location',
        'max_guests',
        'user_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['event_date', 'created_at', 'updated_at', 'deleted_at'];

    // Mối quan hệ Many-to-One với bảng users (một sự kiện được tạo bởi một người dùng)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Kiểm tra trạng thái của sự kiện (upcoming/ongoing/completed/canceled)
    public function isUpcoming()
    {
        return $this->status === 'upcoming';
    }

    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    // Kiểm tra xem sự kiện có còn chỗ trống hay không
    public function hasAvailableSeats($currentGuests)
    {
        return $this->max_guests > $currentGuests;
    }
}
