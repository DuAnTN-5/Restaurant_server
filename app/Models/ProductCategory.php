<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductCategory extends Model
{
    use SoftDeletes;

    // Tên bảng liên kết
    protected $table = 'product_categories';

    // Các cột có thể được phép ghi dữ liệu
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'position',
        'status',
        'created_at',
        'updated_at'
    ];

    // Mối quan hệ tự tham chiếu (Self-referencing): Danh mục con thuộc về danh mục cha
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    // Mối quan hệ tự tham chiếu (Self-referencing): Một danh mục có thể có nhiều danh mục con
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    // Mối quan hệ One-to-Many với bảng products (một danh mục có nhiều sản phẩm)
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Kiểm tra xem danh mục có danh mục con hay không
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    // Lấy slug của danh mục để sử dụng cho URL
    // public function getSlugAttribute()
    // {
    //     return $this->slug;
    // }
    public static function getAllParent(){
        $product_catrgories = DB::table('product_categories')->whereNull('parent_id')->whereNull('deleted_at')->get();
        return $product_catrgories;
    }
}
