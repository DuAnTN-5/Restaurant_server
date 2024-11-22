<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('wards', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('district_id'); // Khóa ngoại liên kết đến bảng districts
        $table->string('name'); // Tên phường/xã
        $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
