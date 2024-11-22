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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên phương thức thanh toán
            $table->text('description')->nullable(); // Mô tả phương thức thanh toán
            $table->string('icon')->nullable(); // Đường dẫn biểu tượng của phương thức
            $table->boolean('is_active')->default(true); // Trạng thái kích hoạt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
