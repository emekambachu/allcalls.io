<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conference_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_call_id')->constrained('conference_calls')->onDelete('cascade');
            $table->string('sid')->unique();
            $table->string('status')->nullable(); // Could be 'joined', 'left', etc.
            $table->string('phone_number')->nullable();
            $table->boolean('muted')->default(false);
            $table->boolean('hold')->default(false);
            $table->boolean('coaching')->default(false);
            $table->string('call_status')->nullable();
            $table->string('reason_left')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_participants');
    }
};
