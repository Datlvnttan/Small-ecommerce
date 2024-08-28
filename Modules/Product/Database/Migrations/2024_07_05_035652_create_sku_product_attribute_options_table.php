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
        // Schema::create('sku_product_attribute_options', function (Blueprint $table) {
        //     $table->unsignedBigInteger('sku_id');
        //     $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
        //     $table->unsignedBigInteger('product_attribute_option_id');
        //     $table->foreign('product_attribute_option_id','fk_product_attribute_option_id')->references('id')->on('product_attribute_options')->onDelete('cascade');
        //     $table->primary(['sku_id','product_attribute_option_id']);
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
        Schema::dropIfExists('sku_product_attribute_options');
    }
};
