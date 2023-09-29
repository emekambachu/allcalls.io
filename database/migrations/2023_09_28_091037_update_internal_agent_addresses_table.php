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
        Schema::table('internal_agent_addresses', function (Blueprint $table) {
            $table->dropColumn('city_state');
            $table->string('city')->nullable()->after('address');
            $table->integer('state')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_addresses', function (Blueprint $table) {
            $table->string('city_state')->nullable();
        });
    }
};
