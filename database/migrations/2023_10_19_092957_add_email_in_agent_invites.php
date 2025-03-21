<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('agent_invites', 'email')) {
            Schema::table('agent_invites', function (Blueprint $table) {
                $table->string('email')->unique()->after('id');
                $table->string('url')->after('token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_invites', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('url');
        });
    }
};
