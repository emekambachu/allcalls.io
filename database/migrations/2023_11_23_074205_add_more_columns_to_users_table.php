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
        if (Schema::hasTable('users')) { // Check if the users table exists
            Schema::table('users', function (Blueprint $table) {
                // Add the invited_by column if it doesn't already exist
                if (!Schema::hasColumn('users', 'invited_by')) {
                    $table->foreignId('invited_by')->nullable()->constrained('users');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) { // Check again if the users table exists
            Schema::table('users', function (Blueprint $table) {
                // Drop the invited_by column if it exists
                if (Schema::hasColumn('users', 'invited_by')) {
                    $table->dropForeign(['invited_by']); // Specify the foreign key constraint name within an array
                    $table->dropColumn('invited_by');
                }
            });
        }
    }
};
