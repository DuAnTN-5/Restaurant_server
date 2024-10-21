<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyProgram extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'loyalty_program';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'user_id',
        'points',
        'membership_level',
        'rewards',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Mối quan hệ Many-to-One với bảng users (mỗi chương trình khách hàng thân thiết thuộc về một người dùng)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Phương thức kiểm tra trạng thái chương trình khách hàng thân thiết
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Nâng cấp cấp độ thành viên dựa trên điểm số
    public function upgradeMembershipLevel()
    {
        if ($this->points >= 1000) {
            $this->membership_level = 'gold';
        } elseif ($this->points >= 500) {
            $this->membership_level = 'silver';
        } else {
            $this->membership_level = 'bronze';
        }
    }

    // Kiểm tra nếu người dùng có đủ điểm để nhận thưởng
    public function canRedeemReward($requiredPoints)
    {
        return $this->points >= $requiredPoints;
    }
}
