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
        Schema::create('autopay_settings', function (Blueprint $table) {
            $table->id();
            $table->float('threshold', 8, 2);
            $table->float('amount', 8, 2);
            $table->boolean('enabled')->default(false);
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('user_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autopay_settings');
    }
};
