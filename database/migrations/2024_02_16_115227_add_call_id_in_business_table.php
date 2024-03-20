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
                if (!Schema::hasColumn('internal_agent_my_businesses', 'call_id')) { // Check if the column doesn't exist
                    $table->foreignId('call_id')
                          ->after('policy_draft_date')
                          ->nullable()
                          ->constrained('calls');
                }
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
                if (Schema::hasColumn('internal_agent_my_businesses', 'call_id')) { // Check if the column exists
                    $table->dropForeign(['call_id']); // Use an array to specify the exact foreign key constraint name
                    $table->dropColumn('call_id');
                }
            });
        }
    }
};
