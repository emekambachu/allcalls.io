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
            if (!Schema::hasColumn('clients', 'call_id')) {
                $table->foreignId('call_id')
                    ->after('user_id')
                    ->constrained('calls')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'call_id')) {
                $table->dropColumn('call_id');
            }

            if (self::hasForeignKey('clients', 'clients_call_id_foreign')) {
                $table->dropForeign(['call_id']);
            }
        });
    }

    public static function hasForeignKey($table, $key)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        $keys =  array_map(function ($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));

        return in_array($key, $keys);
    }
};
