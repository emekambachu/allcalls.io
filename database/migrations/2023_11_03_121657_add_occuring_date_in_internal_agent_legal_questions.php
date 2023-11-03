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
        Schema::table('internal_agent_legal_questions', function (Blueprint $table) {
            $table->string('occuring_date')->after('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_legal_questions', function (Blueprint $table) {
            $table->dropColumn('occuring_date');
        });
    }
};
