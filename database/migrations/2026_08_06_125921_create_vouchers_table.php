<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20)->unique();
            $table->string('batch_id', 20)->nullable()->index();
            $table->enum('status', ['unused', 'active', 'expired'])->default('unused');
            $table->string('device_mac')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unsignedTinyInteger('failed_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();

            $table->index(['business_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
