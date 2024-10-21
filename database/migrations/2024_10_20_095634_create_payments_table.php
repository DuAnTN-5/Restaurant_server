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
            $table->unsignedBigInteger('order_id'); // Liên kết với bảng orders
            $table->unsignedBigInteger('table_id')->nullable(); // Liên kết với bảng tables (có thể null nếu không phải dine-in)
            $table->string('payment_method', 50); // momo, vnpay, cash, credit_card, etc.
            $table->string('payment_status', 50)->default('pending'); // pending, completed, failed
            $table->string('transaction_id', 100)->nullable(); // Mã giao dịch, nếu có
            $table->decimal('amount', 10, 2); // Tổng số tiền
            $table->decimal('tax_amount', 10, 2)->nullable(); // Tiền thuế, nếu có
            $table->decimal('total_amount', 10, 2); // Tổng tiền bao gồm thuế
            $table->text('provider_response')->nullable(); // Phản hồi từ nhà cung cấp thanh toán (momo, vnpay, etc.)
            $table->text('error_message')->nullable(); // Thông báo lỗi khi thanh toán thất bại
            $table->timestamp('payment_date')->nullable(); // Thời gian thanh toán
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
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
