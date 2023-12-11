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
        Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
            $table->string('beneficiary_name');
            $table->string('beneficiary_relationship');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
            $table->dropColumn('beneficiary_name');
            $table->dropColumn('beneficiary_relationship');
            $table->dropColumn('notes');
        });
    }
};
