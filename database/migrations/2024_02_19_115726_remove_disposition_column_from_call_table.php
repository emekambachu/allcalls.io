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
        if (Schema::hasTable('calls')) {
            Schema::table('calls', function (Blueprint $table) {
                if (Schema::hasColumn('calls', 'disposition')) { // Check if the column exists
                    $table->dropColumn('disposition');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('calls')) {
            Schema::table('calls', function (Blueprint $table) {
                if (!Schema::hasColumn('calls', 'disposition')) { // Check if the column doesn't exist
                    $table->string('disposition')->nullable()->after('call_type_id');
                }
            });
        }
    }
};
