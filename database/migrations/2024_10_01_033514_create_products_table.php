<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        // Chỉ tạo bảng nếu chưa tồn tại
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id(); // ID sản phẩm
                $table->string('name'); // Tên sản phẩm
                $table->string('slug')->unique(); // Slug cho URL thân thiện
                $table->text('description')->nullable(); // Mô tả sản phẩm
                $table->text('summary')->nullable(); // Tóm tắt sản phẩm
                $table->decimal('price', 10, 2); // Giá sản phẩm
                $table->unsignedBigInteger('category_id')->nullable(); // Khoá ngoại đến bảng categories
                $table->string('image_url')->nullable(); // Đường dẫn ảnh
                $table->integer('stock_quantity')->default(0); // Số lượng sản phẩm trong kho
                $table->decimal('discount_price', 10, 2)->nullable(); // Giá giảm
                $table->boolean('availability')->default(true); // Tình trạng sẵn có
                $table->text('ingredients')->nullable(); // Thành phần sản phẩm
                $table->integer('position')->default(0); // Thứ tự sắp xếp
                $table->string('tags')->nullable(); // Các thẻ sản phẩm
                $table->string('status', 50)->default('active'); // Tình trạng sản phẩm
                $table->string('product_code')->unique(); // Mã sản phẩm duy nhất
                $table->timestamps(); // created_at và updated_at

                // Định nghĩa khóa ngoại liên kết với bảng categories
                $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
