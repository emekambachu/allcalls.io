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
        Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
            $table->dropColumn('ef_number');
            $table->dropColumn('upline_manager');
            $table->dropColumn('split_sale');
            $table->dropColumn('split_sale_type');
            $table->dropColumn('split_agent_email');
            $table->dropColumn('appointment_type');
            $table->dropColumn('annual_target_premium');
            $table->dropColumn('annual_planned_premium');
            $table->dropColumn('annual_excess_premium');
            $table->dropColumn('intial_investment_amount');
            $table->dropColumn('refer_another_agent');
            $table->dropColumn('this_an_sdic');
            $table->dropColumn('recurring_premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
            $table->string('ef_number');
            $table->string('upline_manager');
            $table->boolean('split_sale');
            $table->string('split_sale_type')->nullable();
            $table->string('split_agent_email');
            $table->string('appointment_type');
            $table->double('annual_target_premium')->nullable();
            $table->double('annual_planned_premium')->nullable();
            $table->double('annual_excess_premium')->nullable();
            $table->double('intial_investment_amount')->nullable();
            $table->boolean('refer_another_agent')->nullable();
            $table->boolean('this_an_sdic')->nullable();
            $table->boolean('recurring_premium')->nullable();
        });
    }
};
