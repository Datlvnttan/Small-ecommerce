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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name');
            $table->string('cover_image')->nullable();
            $table->text('describe')->nullable();
            $table->text('detail')->nullable();
            $table->integer('shipping_point');
            $table->unsignedBigInteger('category_id')->nullable()->index('products_category_id_foreign');
            $table->unsignedBigInteger('brand_id')->nullable()->index('products_brand_id_foreign');
            $table->timestamps();
            $table->double('average_rating', 8, 2)->default(0);
            $table->integer('total_rating')->default(0);
            $table->integer('total_quantity_sold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
