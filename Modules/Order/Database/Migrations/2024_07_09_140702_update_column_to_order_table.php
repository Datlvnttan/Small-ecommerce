<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('orders', function (Blueprint $table) {
        //     $table->enum('status', ['Awaiting Verification', 'Awaiting Confirmation', 'Processing', 'In Transit', 'Delivered', 'Cancelled', 'Rejected'])->default("Awaiting Confirmation")->change();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
        });
    }
};
