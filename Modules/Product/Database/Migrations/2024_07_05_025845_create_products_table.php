<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('products', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('product_name');
        //     $table->string('cover_image')->nullable();
        //     $table->string('describe')->nullable();
        //     $table->string('detail')->nullable();
        //     $table->integer('price_guest');
        //     $table->float('discount_guest')->default(0.0);
        //     $table->integer('shipping_point');
        //     $table->unsignedBigInteger('category_id');
        //     $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        //     $table->unsignedBigInteger('brand_id');
        //     $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        //     $table->timestamps();
        // });
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
};
