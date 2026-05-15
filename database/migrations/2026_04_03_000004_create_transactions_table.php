<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 8, 2)->default(0);
            $table->string('payment_method', 100)->default('card');
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['paid', 'refunded', 'failed'])->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
