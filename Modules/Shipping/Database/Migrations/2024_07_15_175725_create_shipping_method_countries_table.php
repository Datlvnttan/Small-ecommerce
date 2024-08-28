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
        // Schema::create('shipping_method_countries', function (Blueprint $table) {
        //     $table->unsignedBigInteger('shipping_method_id');
        //     $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');
        //     $table->unsignedBigInteger('country_id');
        //     $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        //     $table->primary(['shipping_method_id','country_id']);
        //     $table->integer('expense');
        //     $table->integer('discount');
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
        Schema::dropIfExists('shipping_method_countries');
    }
};
