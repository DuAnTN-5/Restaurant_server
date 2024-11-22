<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNullableColumnsInReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change(); // Cho phép NULL cho order_id
            $table->unsignedBigInteger('staff_id')->nullable()->change(); // Cho phép NULL cho staff_id
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable(false)->change(); // Không cho phép NULL (phục hồi)
            $table->unsignedBigInteger('staff_id')->nullable(false)->change(); // Không cho phép NULL (phục hồi)
        });
    }
}
