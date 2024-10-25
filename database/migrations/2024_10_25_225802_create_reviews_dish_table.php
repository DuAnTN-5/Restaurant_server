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
        Schema::create('reviews_dish', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID người dùng
            $table->foreignId('dish_id')->constrained('products')->onDelete('cascade'); // ID món ăn
            $table->integer('rating')->unsigned()->comment('Số sao đánh giá, từ 1 đến 5'); // Số sao
            $table->text('comment')->nullable(); // Nội dung đánh giá
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews_dish');
    }
};
