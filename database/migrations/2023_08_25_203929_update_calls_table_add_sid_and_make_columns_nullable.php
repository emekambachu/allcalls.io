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
                if (!Schema::hasColumn('calls', 'sid')) { // Check if the 'sid' column doesn't exist
                    $table->string('sid')->nullable();
                }
                // Assuming 'call_duration_in_seconds' and 'hung_up_by' columns already exist and just need to be modified
                $table->integer('call_duration_in_seconds')->nullable()->change();
                $table->string('hung_up_by')->nullable()->change();
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
                $table->dropColumn('sid');
                // Reversing changes made to 'call_duration_in_seconds' and 'hung_up_by'
                // Note: Laravel doesn't support making a column 'not nullable' using the change() method directly without specifying the column's default value or using a DB statement.
                // You might need to use a raw statement or reconsider this part of the rollback logic.
            });
        }
    }
};
