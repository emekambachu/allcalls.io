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
        Schema::table('calls', static function (Blueprint $table) {
            if (!Schema::hasColumn('calls', 'policy_id')) { // Check if the column doesn't exist
                $table->foreignId('policy_id')
                      ->after('user_id')
                      ->nullable()
                      ->constrained('policies'); // Assuming the foreign key is intended to reference the 'policies' table, not 'calls'
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calls', static function (Blueprint $table) {
            if (Schema::hasColumn('calls', 'policy_id')) { // Check if the column exists



                if (self::hasForeignKey('calls', 'calls_policy_id_foreign')) {
                    $table->dropForeign('calls_policy_id_foreign');
                }

                $table->dropForeign(['policy_id']); // Use an array to specify the exact foreign key constraint name
                $table->dropColumn('policy_id');
            }
        });
    }

    public static function hasForeignKey($table, $key) {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        $keys =  array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
        
        return in_array($key, $keys);
    }
};
