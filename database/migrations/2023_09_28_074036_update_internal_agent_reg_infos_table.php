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
        Schema::table('internal_agent_reg_infos', function (Blueprint $table) {
            $table->dropColumn('city_state');
            $table->dropColumn('aml_course');
            $table->dropColumn('omissions_insurance');
            $table->dropColumn('move_in_city_state');
            $table->dropColumn('business_city_state');
            $table->string('city')->nullable()->after('address');
            $table->integer('state')->nullable()->after('city');
            $table->string('move_in_city')->nullable()->after('move_in_address');
            $table->integer('move_in_state')->nullable()->after('move_in_city');
            $table->string('business_city')->nullable()->after('business_address');
            $table->integer('business_state')->nullable()->after('business_city');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_reg_infos', function (Blueprint $table) {
            $table->string('city_state')->nullable();
            $table->boolean('aml_course')->default(0);
            $table->boolean('omissions_insurance')->default(0);
            $table->string('move_in_city_state')->nullable();
            $table->string('business_city_state')->nullable();
        });
    }
};
