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
        // Schema::create('order_details', function (Blueprint $table) {
        //     $table->unsignedBigInteger('order_id');
        //     $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        //     $table->unsignedBigInteger('sku_id');
        //     $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
        //     $table->primary(['order_id','sku_id']);
        //     $table->integer('price');
        //     $table->integer('quantity');
        //     $table->integer('feedback_rating')->nullable();
        //     $table->string('feedback_image')->nullable();
        //     $table->string('feedback_review')->nullable();
        //     $table->tinyInteger('feedback_status')->default(0);
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
        Schema::dropIfExists('order_details');
    }
};
