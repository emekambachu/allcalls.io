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
        Schema::table('internal_agent_legal_questions', function (Blueprint $table) {
            $table->dropColumn('convicted_checkbox_1');
            $table->dropColumn('convicted_checkbox_1a');
            $table->dropColumn('convicted_checkbox_1b');
            $table->dropColumn('convicted_checkbox_1c');
            $table->dropColumn('convicted_checkbox_1d');
            $table->dropColumn('convicted_checkbox_1e');
            $table->dropColumn('convicted_checkbox_1f');
            $table->dropColumn('convicted_checkbox_1g');
            $table->dropColumn('convicted_checkbox_1h');
            $table->dropColumn('lawsuit_checkbox_2');
            $table->dropColumn('lawsuit_checkbox_2a');
            $table->dropColumn('lawsuit_checkbox_2b');
            $table->dropColumn('lawsuit_checkbox_2c');
            $table->dropColumn('lawsuit_checkbox_2d');
            $table->dropColumn('alleged_engaged_checkbox_3');
            $table->dropColumn('found_engaged_checkbox_4');
            $table->dropColumn('terminate_contract_checkbox_5');
            $table->dropColumn('terminate_contract_checkbox_5a');
            $table->dropColumn('terminate_contract_checkbox_5b');
            $table->dropColumn('terminate_contract_checkbox_5c');
            $table->dropColumn('cancel_insu_cause_checkbox_6');
            $table->dropColumn('insurer_checkbox_7');
            $table->dropColumn('lawsuit_checkbox_8');
            $table->dropColumn('lawsuit_checkbox_8a');
            $table->dropColumn('lawsuit_checkbox_8b');
            $table->dropColumn('license_denied_checkbox_9');
            $table->dropColumn('regulatory_checkbox_10');
            $table->dropColumn('regulatory_revoked_checkbox_11');
            $table->dropColumn('regulatory_found_checkbox_12');
            $table->dropColumn('interr_licensing_checkbox_13');
            $table->dropColumn('self_regularity_checkbox_14');
            $table->dropColumn('self_regularity_checkbox_14a');
            $table->dropColumn('self_regularity_checkbox_14b');
            $table->dropColumn('self_regularity_checkbox_14c');
            $table->dropColumn('bankruptcy_checkbox_15');
            $table->dropColumn('bankruptcy_checkbox_15a');
            $table->dropColumn('bankruptcy_checkbox_15b');
            $table->dropColumn('bankruptcy_checkbox_15c');
            $table->dropColumn('liens_against_checkbox_16');
            $table->dropColumn('connected_checkbox_17');
            $table->dropColumn('aliases_checkbox_18');
            $table->dropColumn('unresolved_matter_checkbox_19');
            $table->string('name')->after('reg_info_id')->nullable();
            $table->string('value')->after('name')->nullable();
            $table->string('description')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_legal_questions', function (Blueprint $table) {
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
        });
    }
};
