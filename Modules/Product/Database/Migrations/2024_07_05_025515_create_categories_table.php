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
        // Schema::create('categories', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('category_name');
        //     $table->unsignedBigInteger('parent_category_id')->nullable();
        //     $table->foreign('parent_category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('categories');
    }
};
