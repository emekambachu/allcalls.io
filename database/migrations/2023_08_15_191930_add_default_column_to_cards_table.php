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
        if (Schema::hasTable('cards')) { // Check if the table exists
            Schema::table('cards', function (Blueprint $table) {
                if (!Schema::hasColumn('cards', 'default')) { // Check if the column doesn't exist
                    $table->boolean('default')->default(false);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cards')) { // Check if the table exists
            Schema::table('cards', function (Blueprint $table) {
                if (Schema::hasColumn('cards', 'default')) { // Check if the column exists
                    $table->dropColumn('default');
                }
            });
        }
    }
};
