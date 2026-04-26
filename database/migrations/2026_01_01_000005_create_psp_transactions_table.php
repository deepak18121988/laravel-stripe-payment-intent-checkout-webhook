<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('psp_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('transaction_id');
            $table->unsignedTinyInteger('psp_vendor_id');
            $table->string('psp_intent_id')->unique();
            $table->string('psp_charge_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3);
            $table->string('status', 30);
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('psp_transactions');
    }
};
