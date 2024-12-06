<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;  // Sử dụng SoftDeletes để hỗ trợ xóa mềm

    // Tên bảng liên kết
    protected $table = 'products';

    // Các trường có thể ghi vào cơ sở dữ liệu
    protected $fillable = [
        'name',
        'slug',
        'description',
        'summary',
        'price',
        'category_id',
        'image_url',
        'stock_quantity',
        'discount_price',
        'availability',
        'ingredients',
        'position',
        'status',
        'tags',
        'product_code',
    ];

    // Định dạng cho các trường kiểu ngày
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Quan hệ Many-to-One với bảng product_categories (một sản phẩm thuộc về một danh mục sản phẩm)
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Quan hệ One-to-Many với bảng order_items (một sản phẩm có thể xuất hiện trong nhiều đơn hàng)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Quan hệ One-to-Many với bảng reviews (một sản phẩm có thể được nhiều người dùng đánh giá)
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    // Quan hệ One-to-Many với bảng feedbacks (một sản phẩm có thể nhận nhiều phản hồi từ khách hàng)
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'product_id');
    }

    // Quan hệ Many-to-One với bảng menu_items (sản phẩm có thể xuất hiện trong nhiều mục của thực đơn)
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'product_id');
    }

    // Lấy URL của ảnh sản phẩm hoặc trả về ảnh mặc định nếu không có
    // public function getImageUrlAttribute()
    // {
    //     return $this->image_url ? asset($this->image_url) : asset('default-product-image.png');
    // }

    // Tính giá sau khi áp dụng giảm giá (nếu có)
    public function getFinalPriceAttribute()
    {
        return $this->discount_price ? $this->discount_price : $this->price;
    }

    // Chuyển đổi tags thành mảng để dễ sử dụng
    public function getTagsArray()
    {
        return explode(',', $this->tags);
    }

    // Kiểm tra trạng thái sản phẩm (còn hàng hay hết hàng)
    public function isAvailable()
    {
        return $this->availability === true;
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id'); // 'product_id' là khóa ngoại trong bảng CartItem
    }

    public function getIngredients()
    {
        return collect(json_decode($this->ingredients))->pluck('value')->toArray();
    }
}
