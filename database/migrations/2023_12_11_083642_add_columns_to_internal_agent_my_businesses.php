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
            if (!Schema::hasColumn('internal_agent_my_businesses', 'beneficiary_name')) {
                $table->string('beneficiary_name');
            }
            if (!Schema::hasColumn('internal_agent_my_businesses', 'beneficiary_relationship')) {
                $table->string('beneficiary_relationship');
            }
            if (!Schema::hasColumn('internal_agent_my_businesses', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_agent_my_businesses', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('internal_agent_my_businesses', 'beneficiary_name')) {
                $columnsToDrop[] = 'beneficiary_name';
            }
            if (Schema::hasColumn('internal_agent_my_businesses', 'beneficiary_relationship')) {
                $columnsToDrop[] = 'beneficiary_relationship';
            }
            if (Schema::hasColumn('internal_agent_my_businesses', 'notes')) {
                $columnsToDrop[] = 'notes';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

};
