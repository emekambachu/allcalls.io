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
        Schema::create('internal_agent_additional_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reg_info_id')
                ->constrained('internal_agent_reg_infos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('resident_country')->nullable();
            $table->string('resident_your_home')->nullable();
            $table->string('resident_city_state')->nullable();
            $table->string('resident_maiden_name')->nullable();
            //Anti-Money Laundering
            $table->string('aml_provider')->nullable();
            $table->string('training_completion_date')->nullable();
            $table->string('limra_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_agent_additional_infos');
    }
};
