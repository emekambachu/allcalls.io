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
            $table->foreignId('client_id')
            ->nullable()
            ->constrained('clients')->after('policy_draft_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
    }
};
