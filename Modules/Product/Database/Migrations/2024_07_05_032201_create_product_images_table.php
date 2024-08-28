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
        // Schema::create('product_images', function (Blueprint $table) {
        //     $table->unsignedBigInteger('product_id');
        //     $table->string('image_name');
        //     $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        //     $table->primary(['product_id', 'image_name']);
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
        Schema::dropIfExists('product_images');
    }
};
