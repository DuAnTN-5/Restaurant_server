<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'staff_id',
        'table_id',
        'reservation_date',
        'guest_count',
        'special_requests',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PENDING = 'pending';
    const STATUS_RESERVED = 'reserved';
    const STATUS_IN_USE = 'in_use';
    const STATUS_AVAILABLE = 'available';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCanceled()
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isReserved()
    {
        return $this->status === self::STATUS_RESERVED;
    }

    public function isInUse()
    {
        return $this->status === self::STATUS_IN_USE;
    }

    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isExpired()
    {
        return $this->reservation_date->lt(Carbon::now()->subHours(3));
    }

    public function updateStatusIfExpired()
    {
        if ($this->isReserved() && $this->isExpired()) {
            $this->update(['status' => self::STATUS_AVAILABLE]);
        }

        if ($this->isInUse() && $this->updated_at->lt(Carbon::now()->subHours(3))) {
            $this->update(['status' => self::STATUS_AVAILABLE]);
        }
    }

    // Thêm phương thức reset trạng thái sau khi kết thúc ngày
    public function resetStatusAfterDayEnd()
    {
        $this->where('reservation_date', '<', Carbon::now()->startOfDay())
             ->update(['status' => self::STATUS_AVAILABLE]);
    }

    // Phương thức để cập nhật trạng thái nếu quá hạn 3 giờ
    public function resetStatusAfterThreeHours()
    {
        $this->where('reservation_date', '<', Carbon::now()->subHours(3))
             ->update(['status' => self::STATUS_AVAILABLE]);
    }
}
