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
        if (Schema::hasTable('calls')) { // Check if the 'calls' table exists
            Schema::table('calls', function (Blueprint $table) {
                if (!Schema::hasColumn('calls', 'completed_at')) { // Check if the 'completed_at' column doesn't exist
                    $table->timestamp('completed_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('calls')) { // Check if the 'calls' table exists
            Schema::table('calls', function (Blueprint $table) {
                if (Schema::hasColumn('calls', 'completed_at')) { // Check if the 'completed_at' column exists
                    $table->dropColumn('completed_at');
                }
            });
        }
    }
};
