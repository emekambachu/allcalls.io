<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\OnlineUser;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OnlineUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_prioritizes_internal_agents()
    {
        // Create roles
        $internalAgentRole = Role::factory()->create(['id' => 3]);
        $otherRole = Role::factory()->create(['id' => 4]);

        // Create users
        $internalAgent = User::factory()->create();
        $internalAgent->roles()->attach($internalAgentRole);

        $normalUser = User::factory()->create();
        $normalUser->roles()->attach($otherRole);

        $onlineUsers = new Collection([$normalUser, $internalAgent]);

        // Call your static method
        $sortedUsers = OnlineUser::prioritizeInternalAgents($onlineUsers);

        // The internal agent should be the first item in the sorted list
        $this->assertEquals($internalAgent->id, $sortedUsers->first()->id);

        // The normal user should be the last item in the sorted list
        $this->assertEquals($normalUser->id, $sortedUsers->last()->id);
    }
}
