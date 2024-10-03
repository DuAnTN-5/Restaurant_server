<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('image')->nullable();
                $table->string('phone_number')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('sex', 10)->nullable();

                // Role: 0 = Người dùng thường, 1 = Admin
                $table->integer('role')->default(0);

                // Status: active, inactive
                $table->string('status')->default('active');

                $table->string('province_id')->nullable();
                $table->string('district_id')->nullable();
                $table->string('ward_id')->nullable();
                $table->string('address')->nullable();
                $table->string('facebook_id', 100)->nullable();
                $table->string('google_id', 100)->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
