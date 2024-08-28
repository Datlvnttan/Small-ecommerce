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
        // Schema::create('brands', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('logo');
        //     $table->string('trademark_name');
        //     $table->integer('total_purchases')->default(0);
        //     $table->integer('total_review')->default(0);
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
        Schema::dropIfExists('trademarks');
    }
};
