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
        if (Schema::hasTable('docu_sign_trackers')) { // Check if the table exists
            Schema::table('docu_sign_trackers', function (Blueprint $table) {
                if (Schema::hasColumn('docu_sign_trackers', 'sign_type')) { // Check if the column exists
                    $table->string('sign_type')->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('docu_sign_trackers')) { // Check if the table exists
            Schema::table('docu_sign_trackers', function (Blueprint $table) {
                if (Schema::hasColumn('docu_sign_trackers', 'sign_type')) { // Check if the column exists
                    $table->integer('sign_type')->change();
                }
            });
        }
    }

};
