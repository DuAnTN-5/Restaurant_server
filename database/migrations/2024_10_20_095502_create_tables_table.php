<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number', 10);
            $table->integer('seats');
            $table->string('status', 50)->default('available'); // Trạng thái bàn (available, reserved, etc.)
            $table->string('location', 50)->nullable();
            $table->text('special_features')->nullable(); // Các đặc điểm đặc biệt của bàn
            $table->string('suitable_for_events', 255)->nullable(); // Đặt sự kiện
            $table->text('custom_availability')->nullable(); // Thời gian đặc biệt
            $table->timestamps();
            $table->softDeletes(); // Xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
