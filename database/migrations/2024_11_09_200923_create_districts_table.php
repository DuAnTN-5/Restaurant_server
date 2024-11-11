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
    Schema::create('districts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('province_id'); // Khóa ngoại liên kết đến bảng provinces
        $table->string('name'); // Tên quận/huyện
        $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
