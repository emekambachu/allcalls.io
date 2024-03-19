<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', static function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('zipCode')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('dob')->nullable();
            $table->string('call_id');
            $table->unsignedInteger('user_id');
            $table->boolean('unlocked')->default(false);
            $table->string('beneficiary')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
