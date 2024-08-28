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
        Schema::table('product_attribute_options', function (Blueprint $table) {
            $table->foreign(['product_attribute_id'])->references(['id'])->on('product_attributes')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_attribute_options', function (Blueprint $table) {
            $table->dropForeign('product_attribute_options_product_attribute_id_foreign');
        });
    }
};
