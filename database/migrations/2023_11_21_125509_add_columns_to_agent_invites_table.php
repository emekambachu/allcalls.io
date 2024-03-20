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
                if (!Schema::hasColumn('agent_invites', 'level_id')) { // Check if the column doesn't exist
                    $table->foreignId('level_id')
                        ->after('id')
                        ->nullable()
                        ->constrained('internal_agent_levels')
                        ->cascadeOnDelete()
                        ->cascadeOnUpdate();
                }
                if (!Schema::hasColumn('agent_invites', 'upline_id')) { // Check if the column doesn't exist
                    $table->string('upline_id')->after('email')->nullable();
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
                // It's generally safe to assume the column exists if you're dropping it, but checking can prevent errors
                if (Schema::hasColumn('agent_invites', 'level_id')) { // Check if the column exists
                    // Assuming 'agent_invites_level_id_foreign' is the name of the foreign key constraint
                    $table->dropForeign(['level_id']); 
                    $table->dropColumn('level_id');
                }
                if (Schema::hasColumn('agent_invites', 'upline_id')) { // Check if the column exists
                    $table->dropColumn('upline_id');
                }
            });
        }
    }

};
