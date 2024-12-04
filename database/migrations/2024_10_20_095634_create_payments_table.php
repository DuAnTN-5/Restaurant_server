<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable(); // Liên kết với bảng orders
            $table->unsignedBigInteger('table_id')->nullable(); // Liên kết với bảng tables
            $table->unsignedBigInteger('user_id')->nullable(); // Liên kết với bảng users
            $table->unsignedBigInteger('payment_method_id')->nullable(); // Liên kết với bảng payment_methods
            $table->unsignedBigInteger('coupon_id')->nullable(); // Liên kết với bảng coupons
            $table->string('payment_status', 50)->default('pending'); // pending, completed, failed
            $table->string('transaction_id', 100)->nullable(); // Mã giao dịch, nếu có
            $table->decimal('amount', 10, 2); // Tổng số tiền
            $table->decimal('tax_amount', 10, 2)->nullable(); // Tiền thuế, nếu có
            $table->decimal('total_amount', 10, 2)->nullable(); // Tổng tiền bao gồm thuế
            $table->text('provider_response')->nullable(); // Phản hồi từ nhà cung cấp thanh toán (momo, vnpay, etc.)
            $table->text('error_message')->nullable(); // Thông báo lỗi khi thanh toán thất bại
            $table->timestamp('payment_date')->nullable(); // Thời gian thanh toán
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); // Thêm khóa ngoại cho user_id
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null'); // Thêm khóa ngoại cho payment_method_id
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null'); // Thêm khóa ngoại cho coupon_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
