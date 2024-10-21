<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // Khóa ngoại từ users
            $table->unsignedBigInteger('staff_id')->nullable(); // Khóa ngoại từ staff (có thể null nếu không có nhân viên phụ trách)
            $table->unsignedBigInteger('table_id'); // Khóa ngoại từ tables
            $table->timestamp('reservation_date'); // Ngày giờ đặt bàn
            $table->integer('guest_count'); // Số lượng khách
            $table->text('special_requests')->nullable(); // Các yêu cầu đặc biệt
            $table->string('status', 50); // Trạng thái đặt bàn (confirmed, canceled, pending, etc.)
            $table->timestamps();

            // Thiết lập khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Khi user bị xóa, các đặt bàn tương ứng cũng bị xóa
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null'); // Khi nhân viên bị xóa, trường này được đặt null
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade'); // Khi bàn bị xóa, các đặt bàn liên quan cũng bị xóa
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
