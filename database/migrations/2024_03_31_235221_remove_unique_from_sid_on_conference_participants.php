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
        Schema::table('conference_participants', function (Blueprint $table) {
            // Drop the unique constraint from 'sid'
            $table->dropUnique(['sid']);

            // If you need to explicitly modify the column to ensure it's non-unique
            // (usually not necessary unless changing other properties of the column)
            // $table->string('sid')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conference_participants', function (Blueprint $table) {
            $table->string('sid')->unique()->nullable()->change();
        });
    }
};
