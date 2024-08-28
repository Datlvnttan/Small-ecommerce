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
        // Schema::create('orders', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('user_id')->nullable();
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->json('delivery_address');
        //     $table->string('payment_method');
        //     $table->json('shipping_method');
        //     $table->integer('total_amount');
        //     $table->integer('total_point');
        //     $table->string('status')->default('Chờ xác nhận');
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
        Schema::dropIfExists('orders');
    }
};
