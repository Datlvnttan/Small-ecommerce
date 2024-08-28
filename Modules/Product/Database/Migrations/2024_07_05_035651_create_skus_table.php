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
        // Schema::create('skus', function (Blueprint $table) {
        //     $table->id();
        //     // $table->string('sku_code')->unique();
        //     $table->integer('guest_price');
        //     $table->float('guest_discount')->default(0.0);
        //     $table->integer('member_retail_price');
        //     $table->float('member_retail_discount')->default(0.0);
        //     $table->integer('member_wholesale_price');
        //     $table->float('member_wholesale_discount')->default(0.0);
        //     $table->boolean('default')->default(false);
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
        Schema::dropIfExists('skus');
    }
};
