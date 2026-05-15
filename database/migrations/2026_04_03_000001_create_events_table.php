<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organiser_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('venue');
            $table->string('city');
            $table->string('category', 100);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->integer('total_capacity');
            $table->string('banner_image')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'rejected', 'cancelled'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['starts_at', 'status']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
