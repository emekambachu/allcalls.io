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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('call_taken');
            $table->dropColumn('call_duration_in_seconds');
            $table->dropColumn('hung_up_by');
            $table->dropColumn('amount_spent');
            $table->dropColumn('recording_url');
            $table->dropColumn('call_type_id');
            $table->dropColumn('call_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->timestamp('call_taken')->useCurrent();
            $table->integer('call_duration_in_seconds');
            $table->enum('hung_up_by', ['Caller', 'Agent']);
            $table->decimal('amount_spent', 8, 2)->default(0);
            $table->string('recording_url')->nullable();
            $table->unsignedInteger('call_type_id');
            $table->string('call_id');
        });
    }
};
