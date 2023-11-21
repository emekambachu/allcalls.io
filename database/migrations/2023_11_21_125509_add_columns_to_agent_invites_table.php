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
        Schema::table('agent_invites', function (Blueprint $table) {
            $table->foreignId('level_id')
            ->after('id')
            ->nullable()
            ->constrained('internal_agent_levels')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->string('upline_id')->after('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_invites', function (Blueprint $table) {
            $table->dropForeign('level_id');
            $table->dropColumn('level_id');
            $table->dropColumn('upline_id');
        });
    }
};
