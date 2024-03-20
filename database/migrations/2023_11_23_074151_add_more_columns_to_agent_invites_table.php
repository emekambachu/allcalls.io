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
        if (Schema::hasTable('agent_invites')) { // Check if the table exists
            Schema::table('agent_invites', function (Blueprint $table) {
                if (!Schema::hasColumn('agent_invites', 'invited_by')) { // Check if the column doesn't exist
                    $table->foreignId('invited_by')
                          ->nullable()
                          ->constrained('users');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('agent_invites')) { // Check if the table exists
            Schema::table('agent_invites', function (Blueprint $table) {
                // It's safe to assume the column exists if being dropped, but checking can prevent errors
                if (Schema::hasColumn('agent_invites', 'invited_by')) { // Check if the column exists
                    // Laravel will automatically generate the foreign key name, so you don't need to specify it
                    $table->dropForeign(['invited_by']); 
                    $table->dropColumn('invited_by');
                }
            });
        }
    }
};
