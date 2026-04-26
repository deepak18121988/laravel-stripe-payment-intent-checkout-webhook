<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('code', 30)->unique();
            $table->string('label', 50);
            $table->string('description')->nullable();
            $table->boolean('is_terminal')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payment_statuses');
    }
};
