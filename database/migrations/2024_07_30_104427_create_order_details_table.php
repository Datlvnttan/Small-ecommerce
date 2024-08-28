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
        Schema::create('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('sku_id')->index('order_details_sku_id_foreign');
            $table->double('price', null, 0);
            $table->integer('quantity');
            $table->integer('feedback_rating')->nullable();
            $table->string('feedback_image')->nullable();
            $table->string('feedback_review')->nullable();
            $table->tinyInteger('feedback_status')->nullable()->default(0);
            $table->timestamps();
            $table->dateTime('feedback_created_at')->nullable();
            $table->string('feedback_title')->nullable();
            $table->string('options')->nullable();
            $table->boolean('feedback_is_updated')->nullable()->default(false);
            $table->boolean('feedback_incognito')->nullable()->default(false);

            $table->primary(['order_id', 'sku_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
