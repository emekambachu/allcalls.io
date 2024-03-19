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
        if (Schema::hasTable('calls') && Schema::hasColumn('calls', 'hung_up_by')) {
            Schema::table('calls', function (Blueprint $table) {
                $table->string('hung_up_by')->default('Caller')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('calls') && Schema::hasColumn('calls', 'hung_up_by')) {
            Schema::table('calls', function (Blueprint $table) {
                $table->string('hung_up_by')->nullable()->change();
            });
        }
    }
};
