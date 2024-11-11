<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'status',
    ];

    /**
     * Một phương thức thanh toán có thể được sử dụng trong nhiều giao dịch thanh toán.
     * Liên kết với bảng payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }
}
