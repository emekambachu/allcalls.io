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
        Schema::table('calls', function (Blueprint $table) {
            // First, check if the 'calls' table exists
            if (Schema::hasTable('calls')) {
                // Next, check if the 'sent_to_onscript' column exists in the 'calls' table
                if (Schema::hasColumn('calls', 'sent_to_onscript')) {
                    Schema::table('calls', function (Blueprint $table) {
                        // If the column exists, proceed to change it
                        $table->boolean('sent_to_onscript')->default(0)->change();
                    });
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            // Check for the table and column existence before reverting the changes
            if (Schema::hasTable('calls')) {
                if (Schema::hasColumn('calls', 'sent_to_onscript')) {
                    Schema::table('calls', function (Blueprint $table) {
                        $table->boolean('sent_to_onscript')->nullable()->change();
                    });
                }
            }
        });
    }
};
