<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user', function (Blueprint $table) {
            $table->id('ID');
            $table->binary('uuid')->unique();
            $table->binary('User_Internal_ID')->unique();
            $table->string('Email_Address', 90)->unique();
            $table->string('First_Name', 40);
            $table->string('Last_Name', 40);
            $table->string('password', 100);
            $table->string('cpassword', 100)->nullable();
            $table->string('password_token')->nullable();
            $table->dateTime('password_expiration_time')->nullable();
            $table->integer('Contact_ID')->nullable();
            $table->string('Loyalty_Program_ID')->nullable();
            $table->string('Loyalty_Account_Number')->nullable();
            $table->integer('Alert_ID')->nullable();
            $table->string('Referral_LIST_ID')->nullable();
            $table->integer('Event_ID')->nullable();
            $table->string('Facebook')->nullable();
            $table->string('Twitter')->nullable();
            $table->string('Instagram')->nullable();
            $table->string('service')->nullable();
            $table->string('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('campaign')->nullable();
            $table->dateTime('date_time')->useCurrent();
            $table->dateTime('email_verified_at')->nullable();
            $table->boolean('account_locked')->default(0);
        });
    }

    public function down(): void {
        Schema::dropIfExists('user');
    }
};
