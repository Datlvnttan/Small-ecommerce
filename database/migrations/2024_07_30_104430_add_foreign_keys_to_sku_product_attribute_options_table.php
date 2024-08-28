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
        Schema::table('sku_product_attribute_options', function (Blueprint $table) {
            $table->foreign(['product_attribute_option_id'], 'fk_product_attribute_option_id')->references(['id'])->on('product_attribute_options')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['sku_id'])->references(['id'])->on('skus')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sku_product_attribute_options', function (Blueprint $table) {
            $table->dropForeign('fk_product_attribute_option_id');
            $table->dropForeign('sku_product_attribute_options_sku_id_foreign');
        });
    }
};
