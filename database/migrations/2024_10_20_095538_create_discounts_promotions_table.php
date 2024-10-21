<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts_promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50)->unique();
            $table->string('type', 50); // Giảm giá theo phần trăm hoặc số tiền
            $table->string('promotion_type', 50); // Kiểu khuyến mãi, ví dụ: "flash sale", "buy one get one"
            $table->decimal('value', 10, 2); // Giá trị giảm
            $table->datetime('start_date'); // Sửa từ timestamp thành datetime
            $table->datetime('end_date'); // Sửa từ timestamp thành datetime
            $table->integer('usage_limit')->default(0); // Số lần sử dụng tối đa
            $table->decimal('minimum_order_value', 10, 2)->nullable(); // Giá trị đơn hàng tối thiểu
            $table->text('conditions')->nullable(); // Điều kiện áp dụng
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts_promotions');
    }
}
