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
            Schema::table('internal_agent_my_businesses', static function (Blueprint $table) {
                $table->foreignId('client_id')
                      ->nullable()
                      ->constrained('clients')
                      ->after('policy_draft_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('internal_agent_my_businesses')) { // Check if the table exists
            Schema::table('internal_agent_my_businesses', static function (Blueprint $table) {
                // First, remove the foreign key constraint
                $table->dropForeign(['client_id']); // Laravel expects an array of column names here
                // Then, drop the column
                $table->dropColumn('client_id');
            });
        }
    }
};
