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
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['sku_id'])->references(['id'])->on('skus')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign('order_details_order_id_foreign');
            $table->dropForeign('order_details_sku_id_foreign');
        });
    }
};
