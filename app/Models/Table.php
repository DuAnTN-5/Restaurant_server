<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'seats',
        'status',
        'location',
        'special_features',
        'suitable_for_events',
        'custom_availability',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'table_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'table_id');
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function getSpecialFeatures()
    {
        return $this->special_features;
    }

    public function isSuitableForEvent($eventType)
    {
        return strpos($this->suitable_for_events, $eventType) !== false;
    }

    public function getCustomAvailability()
    {
        return $this->custom_availability;
    }

    // Phương thức để reset trạng thái bàn sau khi kết thúc ngày
    public function resetStatusAfterDayEnd()
    {
        $this->where('status', 'occupied')
             ->update(['status' => 'available']);
    }
}
