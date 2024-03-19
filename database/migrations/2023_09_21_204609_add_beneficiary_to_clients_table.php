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
        if (Schema::hasTable('clients')) { // Check if the table exists
            Schema::table('clients', static function (Blueprint $table) {
                if (!Schema::hasColumn('clients', 'beneficiary')) { // Check if the column doesn't exist
                    $table->string('beneficiary')->nullable(); // Add the column if it doesn't exist
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('clients')) { // Check if the table exists
            Schema::table('clients', function (Blueprint $table) {
                if (Schema::hasColumn('clients', 'beneficiary')) { // Check if the column exists
                    $table->dropColumn('beneficiary'); // Drop the column if it exists
                }
            });
        }
    }
};
