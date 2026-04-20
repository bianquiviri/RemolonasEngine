<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('plan_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->string('delivery_day')->default('Friday');
            $table->date('next_delivery_date')->nullable();
            $table->enum('frequency', ['weekly', 'monthly'])->default('weekly');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
