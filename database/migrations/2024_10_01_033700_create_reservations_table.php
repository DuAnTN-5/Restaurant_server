<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id(); // Khóa chính auto-increment
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Khóa ngoại đến bảng users
            $table->unsignedBigInteger('table_id'); // Phải là kiểu unsignedBigInteger để phù hợp với khóa chính của bảng tables
            $table->timestamp('reservation_date')->useCurrent();
            $table->integer('guest_count');
            $table->string('status', 50);
            $table->text('special_requests')->nullable();
            $table->timestamps();

            // Khóa ngoại đến bảng tables
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
}
