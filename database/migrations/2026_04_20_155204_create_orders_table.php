<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('store_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'picking', 'ready_for_dispatch', 'delivered', 'failed'])->default('pending');
            $table->date('scheduled_delivery_date');
            $table->jsonb('error_log')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
