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
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('fullname');
        //     $table->string('nickname');
        //     $table->string('email')->unique()->nullable();
        //     $table->string('phone_number')->unique()->nullable();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password')->nullable();
        //     $table->string('OTP',6)->nullable();
        //     $table->date('birthday')->nullable();
        //     $table->string('gender',3)->nullable();
        //     $table->integer('point')->default(0);
        //     $table->boolean('newsletter_subscription')->default(true);
        //     $table->boolean('point_expiration_notification')->default(true);
        //     $table->rememberToken();
        //     $table->string('provider_id')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
