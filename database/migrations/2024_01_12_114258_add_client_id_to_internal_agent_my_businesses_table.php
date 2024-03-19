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
        if (Schema::hasTable('internal_agent_my_businesses')) {
            Schema::table('internal_agent_my_businesses', static function (Blueprint $table) {
                if (!Schema::hasColumn('internal_agent_my_businesses', 'client_id')) {
                    $table->foreignId('client_id')
                          ->nullable()
                          ->constrained('clients')
                          ->after('policy_draft_date');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('internal_agent_my_businesses')) {
            Schema::table('internal_agent_my_businesses', static function (Blueprint $table) {
                if (self::hasForeignKey('internal_agent_my_businesses', 'internal_agent_my_businesses_client_id_foreign')) {
                    $table->dropForeign(['client_id']);
                }
                if (Schema::hasColumn('internal_agent_my_businesses', 'client_id')) {
                    $table->dropColumn('client_id');
                }
            });
        }
    }

    /**
     * Check if a foreign key exists on a table.
     */
    public static function hasForeignKey($table, $foreignKey): bool
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();
        $keys = array_map(function ($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));

        return in_array($foreignKey, $keys);
    }
};
