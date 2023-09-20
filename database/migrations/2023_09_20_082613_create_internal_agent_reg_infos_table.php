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
        Schema::create('internal_agent_reg_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('ssn')->nullable()->unique();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('martial_status')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('driver_license_no')->nullable();
            $table->string('driver_license_state')->nullable();
            $table->string('address')->nullable();
            $table->string('city_state')->nullable();
            $table->string('zip')->nullable();
            $table->string('move_in_date')->nullable();
            $table->string('move_in_address')->nullable();
            $table->string('move_in_city_state')->nullable();
            $table->string('move_in_zip')->nullable();
            $table->string('resident_insu_license_no')->nullable();
            $table->string('resident_insu_license_state')->nullable();
            $table->string('doing_business_as')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_tax_id')->nullable();
            $table->string('business_agent_name')->nullable();
            $table->string('business_agent_title')->nullable();
            $table->string('business_company_type')->nullable();
            $table->string('business_insu_license_no')->nullable();
            $table->string('business_office_fax')->nullable();
            $table->string('business_office_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_website')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_city_state')->nullable();
            $table->string('business_zip')->nullable();
            $table->string('business_move_in_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_agent_reg_infos');
    }
};
