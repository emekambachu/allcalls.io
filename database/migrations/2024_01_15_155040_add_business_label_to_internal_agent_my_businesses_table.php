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
        if (Schema::hasTable('internal_agent_my_businesses')) { // Check if the table exists
            Schema::table('internal_agent_my_businesses', static function (Blueprint $table) {
                if (!Schema::hasColumn('internal_agent_my_businesses', 'label')) { // Check if the column doesn't exist
                    $table->text('label')->after('insurance_company')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('internal_agent_my_businesses')) { // Check if the table exists
            Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
                if (Schema::hasColumn('internal_agent_my_businesses', 'label')) { // Check if the column exists
                    $table->dropColumn('label');
                }
            });
        }
    }
};
