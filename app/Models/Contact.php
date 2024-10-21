<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'contacts';

    // Các trường có thể điền vào (Mass Assignment)
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'message',
        'contact_date',
        'status',
    ];

    // Định dạng các trường kiểu ngày
    protected $dates = ['contact_date', 'created_at', 'updated_at'];

    // Mối quan hệ Many-to-One với bảng Users (liên hệ có thể thuộc về một người dùng)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Kiểm tra nếu liên hệ đã được xử lý
    public function isProcessed()
    {
        return $this->status === 'processed';
    }

    // Kiểm tra trạng thái liên hệ (ví dụ: pending, processed)
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
