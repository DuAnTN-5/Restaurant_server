<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'menus';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'name',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['created_at', 'updated_at'];

    // Mối quan hệ One-to-Many với bảng menu_items (một thực đơn có thể chứa nhiều món)
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }

    // Kiểm tra trạng thái của thực đơn (active/inactive)
    public function isActive()
    {
        return $this->status === 'active';
    }
}
