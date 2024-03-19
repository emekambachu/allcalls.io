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
        if (Schema::hasTable('users')) { // Check if the table exists
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'banned')) { // Check if the column doesn't exist
                    $table->boolean('banned')->default(false);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) { // Check if the table exists
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'banned')) { // Check if the column exists
                    $table->dropColumn('banned');
                }
            });
        }
    }
};
