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
        Schema::create('outbound_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('call_sid')->nullable();
            $table->string('parent_call_sid')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->integer('duration')->nullable();
            $table->string('recording_url')->nullable();
            $table->string('twilio_call_token')->nullable();
            $table->decimal('cost', 8, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_calls');
    }
};
