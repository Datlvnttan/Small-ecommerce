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
        Schema::table('product_flash_sales', function (Blueprint $table) {
            $table->foreign(['flash_sale_id'])->references(['id'])->on('flash_sales')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_flash_sales', function (Blueprint $table) {
            $table->dropForeign('product_flash_sales_flash_sale_id_foreign');
            $table->dropForeign('product_flash_sales_product_id_foreign');
        });
    }
};
