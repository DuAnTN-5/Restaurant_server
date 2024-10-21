<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255); // Tên sự kiện
            $table->text('description')->nullable(); // Mô tả sự kiện
            $table->timestamp('event_date'); // Ngày diễn ra sự kiện
            $table->string('location', 255); // Địa điểm
            $table->integer('max_guests'); // Số lượng khách tối đa
            $table->unsignedBigInteger('user_id'); // Khóa ngoại liên kết với bảng users (người tạo sự kiện)
            $table->string('status', 50)->default('upcoming'); // Trạng thái (upcoming, completed, cancelled)
            $table->timestamps();
            $table->softDeletes(); // Xóa mềm

            // Thiết lập khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Khi user bị xóa, sự kiện cũng bị xóa
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
