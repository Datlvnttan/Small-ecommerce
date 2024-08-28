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
        // Schema::create('shipping_methods', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('shipping_method_name');
        //     $table->integer('expense');
        //     $table->float('discount');
        //     $table->integer('delivery_time');
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
        Schema::dropIfExists('shipping_methods');
    }
};
