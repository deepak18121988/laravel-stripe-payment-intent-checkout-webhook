<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('psp_vendors', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedSmallInteger('vendor_code')->unique();
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('psp_vendors');
    }
};
