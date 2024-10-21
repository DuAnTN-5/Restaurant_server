<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('points')->default(0); // Điểm tích lũy
            $table->string('membership_level', 50)->default('bronze'); // bronze, silver, gold, etc.
            $table->text('rewards')->nullable(); // Phần thưởng
            $table->string('status', 50)->default('active'); // active, inactive
            $table->timestamps();
            $table->softDeletes(); // Soft delete timestamp

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loyalty_program');
    }
}
