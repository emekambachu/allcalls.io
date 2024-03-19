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
        if (Schema::hasTable('internal_agent_my_businesses')) { // Check if the table exists
            Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
                $table->dropColumn([
                    'ef_number',
                    'upline_manager',
                    'split_sale',
                    'split_sale_type',
                    'split_agent_email',
                    'appointment_type',
                    'annual_target_premium',
                    'annual_planned_premium',
                    'annual_excess_premium',
                    'intial_investment_amount',
                    'refer_another_agent',
                    'this_an_sdic',
                    'recurring_premium',
                ]);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('internal_agent_my_businesses')) { // Check if the table exists
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
    }

};
