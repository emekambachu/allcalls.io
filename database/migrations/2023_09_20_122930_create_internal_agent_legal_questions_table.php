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
        Schema::create('internal_agent_legal_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reg_info_id')
                ->constrained('internal_agent_reg_infos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            //Checkboxes
            $table->string('convicted_checkbox_1')->nullable();
            $table->string('convicted_checkbox_1a')->nullable();
            $table->string('convicted_checkbox_1b')->nullable();
            $table->string('convicted_checkbox_1c')->nullable();
            $table->string('convicted_checkbox_1d')->nullable();
            $table->string('convicted_checkbox_1e')->nullable();
            $table->string('convicted_checkbox_1f')->nullable();
            $table->string('convicted_checkbox_1g')->nullable();
            $table->string('convicted_checkbox_1h')->nullable();
            $table->string('lawsuit_checkbox_2')->nullable();
            $table->string('lawsuit_checkbox_2a')->nullable();
            $table->string('lawsuit_checkbox_2b')->nullable();
            $table->string('lawsuit_checkbox_2c')->nullable();
            $table->string('lawsuit_checkbox_2d')->nullable();
            $table->string('alleged_engaged_checkbox_3')->nullable();
            $table->string('found_engaged_checkbox_4')->nullable();
            $table->string('terminate_contract_checkbox_5')->nullable();
            $table->string('terminate_contract_checkbox_5a')->nullable();
            $table->string('terminate_contract_checkbox_5b')->nullable();
            $table->string('terminate_contract_checkbox_5c')->nullable();
            $table->string('cancel_insu_cause_checkbox_6')->nullable();
            $table->string('insurer_checkbox_7')->nullable();
            $table->string('lawsuit_checkbox_8')->nullable();
            $table->string('lawsuit_checkbox_8a')->nullable();
            $table->string('lawsuit_checkbox_8b')->nullable();
            $table->string('license_denied_checkbox_9')->nullable();
            $table->string('regulatory_checkbox_10')->nullable();
            $table->string('regulatory_revoked_checkbox_11')->nullable();
            $table->string('regulatory_found_checkbox_12')->nullable();
            $table->string('interr_licensing_checkbox_13')->nullable();
            $table->string('self_regularity_checkbox_14')->nullable();
            $table->string('self_regularity_checkbox_14a')->nullable();
            $table->string('self_regularity_checkbox_14b')->nullable();
            $table->string('self_regularity_checkbox_14c')->nullable();
            $table->string('bankruptcy_checkbox_15')->nullable();
            $table->string('bankruptcy_checkbox_15a')->nullable();
            $table->string('bankruptcy_checkbox_15b')->nullable();
            $table->string('bankruptcy_checkbox_15c')->nullable();
            $table->string('liens_against_checkbox_16')->nullable();
            $table->string('connected_checkbox_17')->nullable();
            $table->string('aliases_checkbox_18')->nullable();
            $table->string('unresolved_matter_checkbox_19')->nullable();
            //Checkboxes End
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_agent_legal_questions');
    }
};
