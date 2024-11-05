<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'discount_promotion_id',
        'discount_applied',
    ];

    protected $dates = ['deleted_at'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function discountPromotion()
    {
        return $this->belongsTo(DiscountPromotion::class, 'discount_promotion_id');
    }

    // Tính tổng giá trị cho sản phẩm trong đơn hàng (có tính giảm giá/khuyến mãi)
    public function getTotalPriceAttribute()
    {
        $price = $this->price;

        if ($this->discountPromotion) {
            if ($this->discountPromotion->type === 'percentage') {
                $price -= ($price * $this->discountPromotion->value / 100);
            } elseif ($this->discountPromotion->type === 'fixed') {
                $price -= $this->discountPromotion->value;
            }

            // Đảm bảo giá không nhỏ hơn 0
            $price = max($price, 0);
        }

        return $price * $this->quantity;
    }
}
