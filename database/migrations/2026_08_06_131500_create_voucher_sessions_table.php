<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voucher_id')->constrained()->cascadeOnDelete();
            $table->string('device_mac');
            $table->string('ip_address')->nullable();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->timestamp('started_at');
            $table->timestamp('expires_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'status']);
            $table->index(['device_mac', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_sessions');
    }
};
