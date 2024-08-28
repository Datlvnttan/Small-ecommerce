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
        Schema::create('seller_products', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['seller_id', 'product_id']);
            $table->boolean('hidden')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_products');
    }
};
