<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('slug', 255)->unique();
            $table->unsignedBigInteger('parent_id')->nullable(); // Nếu có phân cấp danh mục cha-con
            $table->integer('position')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();

            // Thiết lập khóa ngoại nếu có
            $table->foreign('parent_id')->references('id')->on('post_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_categories');
    }
}
