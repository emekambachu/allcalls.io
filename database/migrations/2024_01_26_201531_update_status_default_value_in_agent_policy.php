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
        if (Schema::hasTable('internal_agent_my_businesses') && Schema::hasColumn('internal_agent_my_businesses', 'status')) {
            Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
                $table->string('status')->after('insurance_company')->default("Submitted")->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('internal_agent_my_businesses') && Schema::hasColumn('internal_agent_my_businesses', 'status')) {
            Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
                // It's tricky to reverse a `default` change directly, you might need to consider what the original state was.
                // If the column was nullable without a default, you could do:
                $table->string('status')->after('insurance_company')->nullable()->default(null)->change();
                // Note: Adjusting the `default` might not be necessary if your original schema didn't set one. Adjust as needed.
            });
        }
    }
};
