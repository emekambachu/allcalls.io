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
        Schema::table('calls', function (Blueprint $table) {
            // Add the 'policy_id' column as an unsigned big integer and make it nullable
            $table->unsignedBigInteger('policy_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            // If you need to roll back this migration, simply drop the 'policy_id' column
            $table->dropColumn('policy_id');
        });
    }
};
