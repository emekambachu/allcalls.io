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
        Schema::table('sendbird_users', function (Blueprint $table) {
            $table->string('profile_url')->nullable();
            $table->string('access_token')->nullable();
            $table->boolean('is_online')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_created')->default(false);
            $table->string('phone_number')->nullable();
            $table->boolean('require_auth_for_profile_image')->default(false);
            $table->json('session_tokens')->nullable();
            $table->bigInteger('last_seen_at')->nullable();
            $table->json('discovery_keys')->nullable();
            $table->json('preferred_languages')->nullable();
            $table->boolean('has_ever_logged_in')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sendbird_users', function (Blueprint $table) {
            // Drop the columns added
            $table->dropColumn([
                'profile_url', 'access_token', 'is_online', 'is_active', 'is_created',
                'phone_number', 'require_auth_for_profile_image', 'session_tokens',
                'last_seen_at', 'discovery_keys', 'preferred_languages', 'has_ever_logged_in'
            ]);
        });
    }
};
