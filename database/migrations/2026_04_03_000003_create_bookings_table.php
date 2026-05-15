<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ticket_type_id')->constrained('ticket_types')->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->string('reference')->unique();
            $table->enum('status', ['confirmed', 'cancelled', 'refunded'])->default('confirmed');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
