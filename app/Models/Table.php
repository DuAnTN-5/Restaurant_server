<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    // Các trường có thể điền vào (Mass Assignment)
    protected $fillable = [
        'number',
        'seats',
        'status',
        'location',
        'special_features',
        'suitable_for_events',
        'custom_availability',
    ];

    // Định dạng cho các trường kiểu dữ liệu
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Một bàn có thể có nhiều đặt chỗ
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }

    // Một bàn có thể liên quan đến nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    // Một bàn có thể liên quan đến nhiều khoản thanh toán
    public function payments()
    {
        return $this->hasMany(Payment::class, 'table_id');
    }

    // Một bàn có thể liên quan đến nhiều sự kiện
    public function events()
    {
        return $this->hasMany(Event::class, 'table_id');
    }

    // Trả về trạng thái bàn: Available, Reserved, Occupied, v.v.
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    // Trả về mô tả về các tính năng đặc biệt của bàn
    public function getSpecialFeatures()
    {
        return $this->special_features;
    }

    // Kiểm tra xem bàn có phù hợp cho sự kiện cụ thể nào không
    public function isSuitableForEvent($eventType)
    {
        return strpos($this->suitable_for_events, $eventType) !== false;
    }

    // Kiểm tra tính khả dụng tùy chỉnh của bàn
    public function getCustomAvailability()
    {
        return $this->custom_availability;
    }
}
