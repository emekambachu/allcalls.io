<?php

use App\Models\Role;
use App\Models\User;
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
        $agentRole = Role::whereName('internal-agent')->first();
        $agents = User::select('users.*', 'role_user.role_id')->leftjoin('role_user', 'role_user.user_id', 'users.id')->whereHas('roles', function ($query) use ($agentRole) {
            $query->where('role_id', $agentRole->id);
        })->get();

        foreach ($agents as $agent) {
            if ($agent->agent_access_status == LIVE) {
                $agent->new_agent_call_scheduled = true;
                $agent->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
