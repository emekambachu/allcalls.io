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
        Schema::table('internal_agent_additional_infos', function (Blueprint $table) {
            $table->dropColumn('resident_city_state');
            $table->dropColumn('aml_provider');
            $table->dropColumn('training_completion_date');
            $table->dropColumn('limra_password');
            $table->string('resident_city')->nullable();
            $table->integer('resident_state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_additional_infos', function (Blueprint $table) {
            $table->string('resident_city_state')->nullable();
            $table->string('aml_provider')->nullable();
            $table->string('training_completion_date')->nullable();
            $table->string('limra_password')->nullable();
        });
    }
};
