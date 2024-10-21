<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'menu_items';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'menu_id',
        'product_id',
        'position',
        'created_at',
        'updated_at',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['created_at', 'updated_at'];

    // Mối quan hệ Many-to-One với bảng menu (một món ăn thuộc về một thực đơn cụ thể)
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Mối quan hệ Many-to-One với bảng products (một món ăn thực chất là một sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
