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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('orders_user_id_foreign');
            $table->json('delivery_address');
            $table->string('payment_method');
            $table->json('shipping_method');
            $table->integer('total_point');
            $table->timestamps();
            $table->json('discount_coupon')->nullable();
            $table->json('billing_address');
            $table->string('order_key')->nullable()->unique();
            $table->string('email')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->double('total_amount', null, 0);
            $table->string('note')->nullable();
            $table->json('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
