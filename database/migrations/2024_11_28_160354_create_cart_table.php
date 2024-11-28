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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('table_id');
            $table->string('date'); // Ngày đặt bàn
            $table->time('time'); // Giờ đặt bàn
            $table->integer('guest_count');
            $table->text('notes')->nullable();
            $table->timestamps();

            // khóa ngoại 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
        });

        Schema::create('cart_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id'); // Liên kết với bảng cart
            $table->unsignedBigInteger('product_id'); // Liên kết với bảng sản phẩm
            $table->integer('quantity')->default(1); // Số lượng sản phẩm
        
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
        Schema::dropIfExists('cart_item');
    }
};