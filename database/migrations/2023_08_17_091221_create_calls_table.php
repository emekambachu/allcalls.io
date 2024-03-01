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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('call_taken')->useCurrent();
            $table->integer('call_duration_in_seconds');
            $table->enum('hung_up_by', ['Caller', 'Agent']);
            $table->decimal('amount_spent', 8, 2)->default(0);
            $table->string('recording_url')->nullable();
            $table->unsignedInteger('call_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('calls');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
