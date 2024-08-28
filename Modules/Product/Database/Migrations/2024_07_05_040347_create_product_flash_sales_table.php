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
        // Schema::create('product_flash_sales', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('product_id');
        //     $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        //     $table->unsignedBigInteger('flash_sale_id');
        //     $table->foreign('flash_sale_id')->references('id')->on('flash_sales')->onDelete('cascade');
        //     $table->float('discount');
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
        Schema::dropIfExists('product_flash_sales');
    }
};
