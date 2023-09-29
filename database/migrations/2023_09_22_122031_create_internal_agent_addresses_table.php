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
        Schema::create('internal_agent_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reg_info_id')
                ->constrained('internal_agent_reg_infos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('address')->nullable();
            $table->string('city_state')->nullable();
            $table->string('zip')->nullable();
            $table->string('move_in_date')->nullable();
            $table->string('move_out_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_agent_addresses');
    }
};
