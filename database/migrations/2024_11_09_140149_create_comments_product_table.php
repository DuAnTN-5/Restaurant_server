<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comment_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Liên kết với sản phẩm
            $table->unsignedBigInteger('user_id'); // Liên kết với người dùng đăng bình luận
            $table->unsignedBigInteger('parent_id')->nullable(); // Để liên kết bình luận con với bình luận cha
            $table->text('content'); // Nội dung bình luận
            $table->timestamps(); // Thêm timestamps một lần

            // Các khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comment_product')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_product');
    }
};
