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
        if (Schema::hasTable('calls')) { // Check if the table exists
            Schema::table('calls', function (Blueprint $table) {
                if (!Schema::hasColumn('calls', 'cost')) { // Check if the column doesn't exist
                    $table->float('cost')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('calls')) { // Check if the table exists
            Schema::table('calls', function (Blueprint $table) {
                if (Schema::hasColumn('calls', 'cost')) { // Check if the column exists
                    $table->dropColumn('cost');
                }
            });
        }
    }
};
