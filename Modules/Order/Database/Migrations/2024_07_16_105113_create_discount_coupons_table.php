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
        // Schema::create('discount_coupons', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('coupon_code')->unique();
        //     $table->float('discount');
        //     $table->integer('quantity')->default(0);
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
        Schema::dropIfExists('discount_coupons');
    }
};
