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
        Schema::create('billing_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('billing_addresses_user_id_foreign');
            $table->string('fullname');
            $table->unsignedBigInteger('country_id')->index('billing_addresses_country_id_foreign');
            $table->string('province');
            $table->string('district');
            $table->string('ward');
            $table->string('zip_code', 30);
            $table->string('address_specific');
            $table->string('tax_id_number')->nullable();
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_addresses');
    }
};
