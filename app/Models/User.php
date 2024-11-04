<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // Các trường có thể điền vào được (Mass Assignment)
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'image',
        'phone_number',
        'date_of_birth',
        'sex',
        'role',
        'status',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'facebook_id',
        'google_id',
        'is_verified',
        'verification_token',
        'verification_expires_at',
    ];

    // Các trường sẽ bị ẩn khi trả về dữ liệu
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Định nghĩa các trường có kiểu ngày tháng
    protected $dates = ['deleted_at'];

    // Định nghĩa kiểu dữ liệu cho các trường khác
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date', // Đảm bảo rằng ngày sinh có kiểu ngày tháng
    ];

    // Quan hệ với bảng staff
    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id');
    }

    // Kiểm tra nếu người dùng là Manager
    public function isManager()
    {
        return $this->role === 2; // 2 = Manager
    }

    // Kiểm tra nếu người dùng là Admin
    public function isAdmin()
    {
        return $this->role === 1; // 1 = Admin
    }

    // Gửi email xác thực
    public function sendEmailVerification()
    {
        Mail::to($this->email)->send(new EmailVerification($this));
    }

    // Kiểm tra nếu người dùng đang hoạt động
    public function isActive()
    {
        return $this->status === 'active'; // Kiểm tra trạng thái active
    }

    // Kiểm tra nếu người dùng bị cấm
    public function isBanned()
    {
        return $this->status === 'banned'; // Kiểm tra trạng thái banned
    }

    // Khôi phục người dùng đã bị xóa mềm
    public static function restoreDeleted($id)
    {
        $user = static::withTrashed()->find($id);
        if ($user) {
            $user->restore(); // Khôi phục người dùng đã xóa
        }
        return $user;
    }

    // Trả về URL avatar của người dùng
    public function getAvatarUrl()
    {
        return $this->image ? asset($this->image) : asset('default-avatar.png'); // Trả về hình ảnh người dùng hoặc hình mặc định
    }

    // Boot method để gắn các sự kiện (events)
    protected static function boot()
    {
        parent::boot();

        // Khi người dùng được tạo mới
        static::created(function ($user) {
            $user->sendEmailVerification();
            \Log::info('Người dùng mới đã được tạo: ' . $user->email);
        });

        // Khi người dùng được cập nhật
        static::updated(function ($user) {
            \Log::info('Người dùng đã được cập nhật: ' . $user->email);
        });

        // Khi người dùng bị xóa mềm
        static::deleted(function ($user) {
            \Log::info('Người dùng đã bị xóa: ' . $user->email);
        });
    }

    // Ví dụ: Quan hệ với bảng Orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    // Ví dụ: Quan hệ với bảng Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id');
    }

    public function loyaltyProgram()
    {
        return $this->hasOne(LoyaltyProgram::class, 'user_id');
    }

    // Quan hệ với bảng Events
    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }
}
