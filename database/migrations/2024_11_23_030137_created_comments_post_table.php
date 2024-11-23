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
        Schema::create('comments_post', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id'); // Liên kết với sản phẩm
            $table->unsignedBigInteger('user_id'); // Liên kết với người dùng đăng bình luận
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('content'); // Nội dung bình luận
            $table->timestamps(); // Thêm timestamps một lần

            // Các khóa ngoại
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments_post')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments_post');
    }
};
