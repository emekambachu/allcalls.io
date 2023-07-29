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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('zipCode')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('dob')->nullable();
            $table->timestamp('call_taken')->useCurrent();
            $table->integer('call_duration_in_seconds');
            $table->enum('hung_up_by', ['Caller', 'Agent']);
            $table->decimal('amount_spent', 8, 2)->default(0);
            $table->string('call_id');
            $table->string('recording_url')->nullable();
            $table->unsignedInteger('call_type_id');
            $table->unsignedInteger('user_id');
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
