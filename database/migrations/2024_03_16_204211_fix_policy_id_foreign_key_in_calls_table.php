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
            // Drop the foreign key constraint using Laravel's default naming convention
            $table->dropForeign(['policy_id']); // The array notation is used here for clarity

            // Then, drop the 'policy_id' column
            $table->dropColumn('policy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            // Re-add the 'policy_id' column
            $table->foreignId('policy_id')->after('user_id')->nullable();

            // Re-add the foreign key constraint, assuming it should reference the 'id' column on the 'calls' table
            // This might need to be adjusted to reference the correct table and column
            $table->foreign('policy_id')->references('id')->on('calls');
        });
    }
};
