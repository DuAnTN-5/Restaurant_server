<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng tính năng Soft Deletes

    protected $table = 'order_items';  // Tên bảng

    // Các trường có thể điền vào (Mass Assignment)
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
        'discount_promotion_id',  // Mã giảm giá hoặc khuyến mãi áp dụng cho sản phẩm (nếu có)
        'discount_applied',
    ];

    // Định dạng cho các trường kiểu date
    protected $dates = ['deleted_at'];

    // Mối quan hệ với bảng Order (nhiều mục hàng thuộc về một đơn hàng)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Mối quan hệ với bảng Product (một mục hàng thuộc về một sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Mối quan hệ với bảng DiscountPromotion (nếu mục hàng có chương trình giảm giá hoặc khuyến mãi áp dụng)
    public function discountPromotion()
    {
        return $this->belongsTo(DiscountPromotion::class, 'discount_promotion_id');
    }

    // Tính tổng giá trị cho sản phẩm trong đơn hàng (có tính giảm giá/khuyến mãi)
    public function getTotalPriceAttribute()
    {
        $price = $this->price;

        // Áp dụng giảm giá nếu có
        if ($this->discountPromotion) {
            if ($this->discountPromotion->type === 'percentage') {
                $price -= ($price * $this->discountPromotion->value / 100);
            } elseif ($this->discountPromotion->type === 'fixed') {
                $price -= $this->discountPromotion->value;
            }
        }

        // Tính tổng giá trị cho sản phẩm (số lượng * giá sau khi áp dụng giảm giá/khuyến mãi)
        return $price * $this->quantity;
    }
}
