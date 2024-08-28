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
        Schema::table('shipping_method_countries', function (Blueprint $table) {
            $table->foreign(['country_id'])->references(['id'])->on('countries')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['shipping_method_id'])->references(['id'])->on('shipping_methods')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_method_countries', function (Blueprint $table) {
            $table->dropForeign('shipping_method_countries_country_id_foreign');
            $table->dropForeign('shipping_method_countries_shipping_method_id_foreign');
        });
    }
};
