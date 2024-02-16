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
            $table->foreignId('call_id')
            ->after('policy_draft_date')
            ->nullable()
            ->constrained('calls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
            $table->dropForeign('call_id');
            $table->dropColumn('call_id');
        });
    }
};
