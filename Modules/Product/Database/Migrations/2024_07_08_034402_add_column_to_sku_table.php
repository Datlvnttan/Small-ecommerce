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
        // Schema::table('skus', function (Blueprint $table) {
        //     $table->unsignedBigInteger('product_id');
        //     $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        //     $table->string('product_part_number')->unique();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sku', function (Blueprint $table) {

        });
    }
};
