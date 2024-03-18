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
        // Check if the 'calls' table exists before adding the column
        if (Schema::hasTable('calls')) {
            Schema::table('calls', function (Blueprint $table) {
                // Add the 'sent_to_onscript' column if it does not exist
                if (!Schema::hasColumn('calls', 'sent_to_onscript')) {
                    $table->boolean('sent_to_onscript');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check for the table's existence before attempting to drop the column
        if (Schema::hasTable('calls')) {
            Schema::table('calls', function (Blueprint $table) {
                // Check if the 'sent_to_onscript' column exists before dropping it
                if (Schema::hasColumn('calls', 'sent_to_onscript')) {
                    $table->dropColumn('sent_to_onscript');
                }
            });
        }
    }
};
