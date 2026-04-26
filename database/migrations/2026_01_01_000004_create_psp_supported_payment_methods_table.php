<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('psp_supported_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('psp_vendor_id');
            $table->string('name', 50);
            $table->string('label', 100)->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('psp_supported_payment_methods');
    }
};
