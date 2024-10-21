<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menu_id'); // Liên kết với bảng menu
            $table->unsignedBigInteger('product_id'); // Liên kết với bảng products
            $table->integer('position')->nullable(); // Vị trí sắp xếp của sản phẩm trong menu
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('menu_id')->references('id')->on('menu')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
