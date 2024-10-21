<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('code', 50)->unique();
            $table->string('discount_type', 50); // Phần trăm hoặc số tiền
            $table->decimal('value', 10, 2);
            $table->datetime('start_date')->nullable(); // Thay đổi sang datetime và có thể nullable
            $table->datetime('end_date')->nullable(); // Thay đổi sang datetime và có thể nullable
            $table->integer('usage_limit')->default(0);
            $table->decimal('minimum_order_value', 10, 2)->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
