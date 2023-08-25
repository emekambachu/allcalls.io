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
            $table->string('sid')->nullable();
            $table->integer('call_duration_in_seconds')->nullable()->change();
            $table->string('hung_up_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            $table->dropColumn('sid');
            $table->integer('call_duration_in_seconds')->nullable(false)->change();
            $table->string('hung_up_by')->nullable(false)->change();
        });
    }
};
