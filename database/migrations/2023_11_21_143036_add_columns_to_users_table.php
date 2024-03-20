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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'level_id')) {
                    $table->foreignId('level_id')
                          ->nullable()
                          ->constrained('internal_agent_levels');
                }
                if (!Schema::hasColumn('users', 'upline_id')) {
                    $table->string('upline_id')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Checking for the 'level_id' column before dropping might be redundant since you're dropping it anyway,
                // but it's included here for consistency and to avoid errors in case the column doesn't exist.
                if (Schema::hasColumn('users', 'level_id')) {
                    // Assuming 'users_level_id_foreign' is the name of the foreign key constraint
                    $table->dropForeign(['level_id']);
                    $table->dropColumn('level_id');
                }
                if (Schema::hasColumn('users', 'upline_id')) {
                    $table->dropColumn('upline_id');
                }
            });
        }
    }
};
