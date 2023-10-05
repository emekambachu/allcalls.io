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
        Schema::create('internal_agent_contract_signeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reg_info_id')
                ->constrained('internal_agent_reg_infos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('accompanying_sign')->nullable();
            $table->text('accompanying_sign_url')->nullable();
            $table->string('signature_authorization')->nullable();
            $table->text('signature_authorization_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_agent_contract_signeds');
    }
};
