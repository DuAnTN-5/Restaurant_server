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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Mối quan hệ với bảng users
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null'); // Mối quan hệ với bảng orders
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null'); // Mối quan hệ với bảng products
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone_number', 20)->nullable();
            $table->text('message');
            $table->timestamp('feedback_date')->useCurrent();
            $table->string('status', 50)->default('new');
            $table->timestamps();
            $table->softDeletes(); // Soft delete cho bảng feedbacks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
