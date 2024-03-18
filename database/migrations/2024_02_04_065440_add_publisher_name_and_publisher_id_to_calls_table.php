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
                // Check if the columns don't exist before adding them
                if (!Schema::hasColumn('calls', 'publisher_name')) {
                    $table->string('publisher_name')->nullable();
                }
                if (!Schema::hasColumn('calls', 'publisher_id')) {
                    $table->string('publisher_id')->nullable();
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
                // Collect columns that exist to drop them in a single operation
                $columnsToDrop = [];
                if (Schema::hasColumn('calls', 'publisher_name')) {
                    $columnsToDrop[] = 'publisher_name';
                }
                if (Schema::hasColumn('calls', 'publisher_id')) {
                    $columnsToDrop[] = 'publisher_id';
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
