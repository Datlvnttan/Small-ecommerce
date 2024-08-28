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
        Schema::create('sku_product_attribute_options', function (Blueprint $table) {
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('product_attribute_option_id')->index('fk_product_attribute_option_id');
            $table->timestamps();

            $table->primary(['sku_id', 'product_attribute_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_product_attribute_options');
    }
};
