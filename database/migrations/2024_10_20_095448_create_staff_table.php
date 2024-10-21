<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('position', 50)->nullable(); // Quản lý, nhân viên, etc.
            $table->timestamp('hire_date')->nullable();
            $table->string('department', 50)->nullable();
            $table->decimal('salary', 10, 2)->default(0);
            $table->string('status', 50)->default('active'); // active, inactive, terminated
            $table->timestamp('shift_start')->nullable();
            $table->timestamp('shift_end')->nullable();
            $table->text('task_description')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Thời gian xóa mềm

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Xóa cascade khi user bị xóa
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
