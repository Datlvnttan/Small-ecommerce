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
        // Schema::create('addresses', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('user_id');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->string('fullname');
        //     $table->unsignedBigInteger('country_id');
        //     $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        //     $table->string('province');
        //     $table->string('district');
        //     $table->string('ward');
        //     $table->string('zip_code',30);
        //     $table->string('address_specific');
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
        Schema::dropIfExists('address');
    }
};
