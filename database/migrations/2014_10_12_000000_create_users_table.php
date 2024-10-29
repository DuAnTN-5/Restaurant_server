<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('image')->nullable();
                $table->string('phone_number', 20)->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('sex', 10)->nullable();
                $table->tinyInteger('role')->default(0); // 0: User, 1: Admin, 2: Manager, 3: Staff
                $table->string('province_code', 10)->nullable();
                $table->string('district_code', 10)->nullable();
                $table->string('ward_code', 10)->nullable();
                $table->string('address')->nullable();
                $table->string('facebook_id', 100)->nullable();
                $table->string('google_id', 100)->nullable();
                $table->string('remember_token', 100)->nullable();
                $table->string('status', 50)->default('active'); // active, inactive, banned
                $table->timestamps();
                $table->softDeletes(); // Thời gian xóa mềm
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
