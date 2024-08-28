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
        Schema::create('shipping_method_countries', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_method_id');
            $table->unsignedBigInteger('country_id')->index('shipping_method_countries_country_id_foreign');
            $table->integer('expense');
            $table->float('discount')->nullable();
            $table->timestamps();
            $table->integer('delivery_time')->default(0);

            $table->primary(['shipping_method_id', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_method_countries');
    }
};
