<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Kiểm tra nếu bảng chưa tồn tại thì mới tạo
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('staff_id')->nullable();
                $table->integer('rating'); // Xếp hạng từ 1-5
                $table->integer('rating_count')->default(0);
                $table->text('comment')->nullable();
                $table->timestamps();
                $table->softDeletes(); // Xóa mềm

                // Foreign keys
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Liên kết với users
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); // Liên kết với products
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Liên kết với orders
                $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null'); // Nhân viên có thể null nếu bị xóa
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
