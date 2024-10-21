<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->text('summary')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('category_id');
            $table->string('image_url', 255)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('availability')->default(true);
            $table->text('ingredients')->nullable();
            $table->integer('position')->nullable();
            $table->string('slug', 255)->unique();
            $table->string('tags', 255)->nullable();
            $table->string('status', 50)->default('active'); // active, inactive, etc.
            $table->string('product_code', 100)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Xóa mềm

            // Khóa ngoại
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
