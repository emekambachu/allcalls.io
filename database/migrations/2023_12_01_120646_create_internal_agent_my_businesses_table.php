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
        Schema::create('internal_agent_my_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('agent_full_name');
            $table->string('agent_email');
            $table->string('ef_number');
            $table->string('upline_manager');
            $table->boolean('split_sale');
            $table->string('split_sale_type')->nullable();
            $table->string('split_agent_email');
            $table->string('insurance_company');
            $table->string('product_name');
            $table->date('application_date');
            $table->double('coverage_amount');
            $table->string('coverage_length');
            $table->string('premium_frequency');
            $table->double('premium_amount');
            $table->double('premium_volumn');
            $table->boolean('equis_writing_number_carrier')->nullable();
            $table->string('carrier_writing_number')->nullable();
            $table->string('this_app_from_lead');
            $table->string('source_of_lead')->nullable();
            $table->string('appointment_type');
            $table->date('policy_draft_date');
            $table->string('first_name');
            $table->string('mi')->nullable();
            $table->double('annual_target_premium')->nullable();
            $table->double('annual_planned_premium')->nullable();
            $table->double('annual_excess_premium')->nullable();
            $table->double('intial_investment_amount')->nullable();
            $table->boolean('refer_another_agent')->nullable();
            $table->boolean('this_an_sdic')->nullable();
            $table->boolean('recurring_premium')->nullable();
            $table->string('last_name');
            $table->date('dob');
            $table->string('gender');
            $table->string('client_street_address_1')->nullable();
            $table->string('client_street_address_2')->nullable();
            $table->string('client_city')->nullable();
            $table->foreignId('client_state')
                ->nullable()
                ->constrained('states');
            $table->string('client_zipcode')->nullable();
            $table->string('client_phone_no');
            $table->string('client_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_agent_my_businesses');
    }
};
