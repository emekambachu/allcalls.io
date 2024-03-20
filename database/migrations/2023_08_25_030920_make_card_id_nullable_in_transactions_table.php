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
        if (Schema::hasTable('transactions')) { // Check if the table exists
            Schema::table('transactions', function (Blueprint $table) {
                if (Schema::hasColumn('transactions', 'card_id')) { // Check if the column exists
                    $table->unsignedBigInteger('card_id')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions')) { // Check if the table exists
            Schema::table('transactions', function (Blueprint $table) {
                if (Schema::hasColumn('transactions', 'card_id')) { // Check if the column exists
                    $table->unsignedBigInteger('card_id')->nullable(false)->change();
                }
            });
        }
    }
};
