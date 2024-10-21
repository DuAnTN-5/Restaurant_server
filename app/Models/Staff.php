<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff'; // Bảng 'staff'

    // Các trường có thể điền vào (Mass Assignment)
    protected $fillable = [
        'name',
        'position',
        'hire_date',
        'department',
        'salary',
        'status',
        'user_id', // Liên kết với bảng users
        'shift_start',
        'shift_end',
        'task_description'
    ];

    // Định dạng cho các trường kiểu dữ liệu
    protected $casts = [
        'hire_date' => 'datetime',
        'salary' => 'decimal:2',
        'shift_start' => 'datetime',
        'shift_end' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Mối quan hệ với bảng users (one-to-one or one-to-many)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mối quan hệ với bảng reservations (one-to-many)
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id'); // Liên kết với bảng reservations qua table_id
    }

    // Mối quan hệ với bảng orders (one-to-many)
    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id'); // Liên kết với bảng orders qua table_id
    }

    // Mối quan hệ với bảng payments (one-to-many)
    public function payments()
    {
        return $this->hasMany(Payment::class, 'table_id'); // Liên kết với bảng payments qua table_id
    }

    // Lấy email từ bảng users
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    // Lấy số điện thoại từ bảng users
    public function getPhoneNumberAttribute()
    {
        return $this->user ? $this->user->phone_number : null;
    }

    // Lấy địa chỉ từ bảng users
    public function getAddressAttribute()
    {
        return $this->user ? $this->user->address : null;
    }

    // Lấy đường dẫn ảnh đại diện từ bảng users
    public function getAvatarUrl()
    {
        return $this->user && $this->user->image ? asset($this->user->image) : asset('default-avatar.png');
    }

    // Kiểm tra nếu nhân viên đang hoạt động
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Tính toán lương hàng tháng
    public function calculateMonthlySalary()
    {
        return $this->salary; // Lương tháng cố định
    }

    // Tính toán lương hàng ngày
    public function calculateDailySalary()
    {
        $monthlySalary = $this->salary;
        $daysInMonth = Carbon::now()->daysInMonth; // Lấy số ngày trong tháng hiện tại
        return $monthlySalary / $daysInMonth;
    }

    // Tính toán lương theo giờ
    public function calculateHourlySalary($hoursWorked)
    {
        $dailySalary = $this->calculateDailySalary(); // Lương ngày
        return ($dailySalary / 8) * $hoursWorked; // Giả sử 8 giờ làm việc mỗi ngày
    }

    // Trả về tên đầy đủ của nhân viên
    public function getFullName()
    {
        return $this->name;
    }

    // Ví dụ: Tính thời gian làm việc của nhân viên từ ngày thuê đến hiện tại
    public function getWorkingDuration()
    {
        return Carbon::parse($this->hire_date)->diffForHumans();
    }

    // Ví dụ khác: Kiểm tra nếu nhân viên là Quản lý
    public function isManager()
    {
        return $this->position === 'Quản lý';
    }
}
