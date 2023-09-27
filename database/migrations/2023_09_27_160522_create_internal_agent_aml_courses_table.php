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
        Schema::create('internal_agent_aml_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reg_info_id')
                ->constrained('internal_agent_reg_infos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('name')->nullable();
            $table->text('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_agent_aml_courses');
    }
};
