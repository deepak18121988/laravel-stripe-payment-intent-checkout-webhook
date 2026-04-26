<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->increments('Transaction_ID');
            $table->unsignedInteger('Listing_ID');
            $table->binary('Buyer_ID');
            $table->unsignedBigInteger('Payment_ID')->nullable();
            $table->string('Purchase_Ref_Number', 11);
            $table->decimal('Sale_Price', 10, 2);
            $table->decimal('App_Fee', 10, 2)->nullable();
            $table->dateTime('Transaction_Date')->useCurrent();
            $table->unsignedTinyInteger('payment_status_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('booking_transactions');
    }
};
