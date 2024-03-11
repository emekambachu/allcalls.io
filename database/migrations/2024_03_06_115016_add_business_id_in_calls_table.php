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
        Schema::table('calls', static function (Blueprint $table) {
            $table->foreignId('policy_id')
                ->after('user_id')
                ->nullable()
                ->constrained('calls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calls', static function (Blueprint $table) {
            $table->dropForeign('policy_id');
            $table->dropColumn('policy_id');
        });
    }
};
