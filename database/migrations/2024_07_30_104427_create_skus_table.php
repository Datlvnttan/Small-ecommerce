<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('guest_price');
            $table->double('guest_discount', 8, 2)->default(0);
            $table->integer('member_retail_price');
            $table->double('member_retail_discount', 8, 2)->default(0);
            $table->integer('member_wholesale_price');
            $table->double('member_wholesale_discount', 8, 2)->default(0);
            $table->boolean('default')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('product_id')->index('skus_product_id_foreign');
            $table->string('product_part_number')->nullable()->unique();
            $table->string('option')->nullable();
            $table->integer('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
