<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->unsignedBigInteger('discount_promotion_id')->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->string('order_type', 50); // Dine-in, Delivery, Pickup
            $table->timestamp('order_date');
            $table->decimal('total_price', 10, 2);
            $table->string('payment_status', 50); // Pending, Completed, etc.
            $table->string('status', 50); // Pending, Completed, Cancelled
            $table->text('delivery_address')->nullable();
            $table->timestamp('estimated_delivery_time')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete timestamp

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('discount_promotion_id')->references('id')->on('discounts_promotions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
