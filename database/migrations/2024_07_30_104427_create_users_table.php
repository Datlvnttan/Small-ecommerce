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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname');
            $table->string('nickname');
            $table->string('email')->nullable()->unique();
            $table->string('phone_number')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('OTP', 6)->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 7)->nullable();
            $table->integer('point')->default(0);
            $table->boolean('newsletter_subscription')->default(true);
            $table->boolean('point_expiration_notification')->default(true);
            $table->rememberToken();
            $table->string('provider_id')->nullable();
            $table->timestamps();
            $table->dateTime('otp_renew_at')->nullable();
            $table->enum('member_type', ['retail', 'wholesale'])->default('retail');
            $table->string('email_change_request', 50)->nullable();
            $table->string('token_change_email', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
