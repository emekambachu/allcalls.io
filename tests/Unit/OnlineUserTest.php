<?php

namespace Tests\Unit;

use App\Models\Bid;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\CallType;
use App\Models\OnlineUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OnlineUserTest extends TestCase
{
    use RefreshDatabase;

    public function testScopeWithSufficientBalance(): void
    {
        // Seed roles
        $roles = ['admin', 'user', 'internal-agent'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $user1 = User::factory()->create(['balance' => 35.0]); // Regular user with just enough balance
        $user2 = User::factory()->create(['balance' => 25.0]); // Regular user with insufficient balance
        $internalAgent = User::factory()->create(['balance' => 30.0]); // Internal agent with insufficient balance
        $internalAgent->roles()->attach(Role::where('name', 'internal-agent')->first());

        $callType = CallType::create(['type' => 'Auto Insurance']);

        // Placing some bids
        Bid::create(['user_id' => $user1->id, 'call_type_id' => $callType->id, 'amount' => 40.0]);
        Bid::create(['user_id' => $user2->id, 'call_type_id' => $callType->id, 'amount' => 30.0]);

        OnlineUser::create(['user_id' => $user1->id, 'call_type_id' => $callType->id]);
        OnlineUser::create(['user_id' => $user2->id, 'call_type_id' => $callType->id]);
        OnlineUser::create(['user_id' => $internalAgent->id, 'call_type_id' => $callType->id]);

        $usersWithSufficientBalance = OnlineUser::withSufficientBalance()->get();

        // Assert only $user1 (the highest bidder with $1 more than the second-highest bid) is in the list
        $this->assertCount(1, $usersWithSufficientBalance);
        $this->assertEquals($user1->id, $usersWithSufficientBalance->first()->user_id);

    }

    public function testScopeWithSufficientBalanceForInternalUsers(): void
    {
        // Seed roles
        $roles = ['admin', 'user', 'internal-agent'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $internalAgentWithSufficientBalance = User::factory()->create(['balance' => 35.0]);
        $internalAgentWithSufficientBalance->roles()->attach(Role::where('name', 'internal-agent')->first());

        $internalAgentWithInsufficientBalance = User::factory()->create(['balance' => 30.0]);
        $internalAgentWithInsufficientBalance->roles()->attach(Role::where('name', 'internal-agent')->first());

        $callType = CallType::create(['type' => 'Auto Insurance']);

        OnlineUser::create(['user_id' => $internalAgentWithSufficientBalance->id, 'call_type_id' => $callType->id]);
        OnlineUser::create(['user_id' => $internalAgentWithInsufficientBalance->id, 'call_type_id' => $callType->id]);

        $usersWithSufficientBalanceForInternal = OnlineUser::withSufficientBalance()->get();

        // Assert only the internal agent with sufficient balance is in the list
        $this->assertCount(1, $usersWithSufficientBalanceForInternal);
        $this->assertEquals($internalAgentWithSufficientBalance->id, $usersWithSufficientBalanceForInternal->first()->user_id);

        // Additionally, verify that no regular user is in this list
        $this->assertEmpty($usersWithSufficientBalanceForInternal->where('user_id', '<>', $internalAgentWithSufficientBalance->id));
    }

    public function testSortByCallPriority(): void
    {
        // Seed roles
        $roles = ['admin', 'user', 'internal-agent'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    
        $callType = CallType::create(['type' => 'Auto Insurance']);
        
        // Internal agents
        $internalAgent1 = User::factory()->create(['last_called_at' => now()->subHours(3)]);
        $internalAgent1->roles()->attach(Role::where('name', 'internal-agent')->first());
        OnlineUser::create(['user_id' => $internalAgent1->id, 'call_type_id' => $callType->id]);
        
        $internalAgent2 = User::factory()->create(['last_called_at' => now()->subHours(1)]);
        $internalAgent2->roles()->attach(Role::where('name', 'internal-agent')->first());
        OnlineUser::create(['user_id' => $internalAgent2->id, 'call_type_id' => $callType->id]);
    
        // Regular users
        $user1 = User::factory()->create(['last_called_at' => now()->subHours(4)]);
        Bid::create(['user_id' => $user1->id, 'call_type_id' => $callType->id, 'amount' => 40.0]);
        OnlineUser::create(['user_id' => $user1->id, 'call_type_id' => $callType->id]);
    
        $user2 = User::factory()->create(['last_called_at' => now()->subHours(2)]);
        Bid::create(['user_id' => $user2->id, 'call_type_id' => $callType->id, 'amount' => 40.0]); // same bid as user1
        OnlineUser::create(['user_id' => $user2->id, 'call_type_id' => $callType->id]);
    
        $user3 = User::factory()->create(['last_called_at' => now()->subHours(5)]);
        Bid::create(['user_id' => $user3->id, 'call_type_id' => $callType->id, 'amount' => 30.0]);
        OnlineUser::create(['user_id' => $user3->id, 'call_type_id' => $callType->id]);
    
        $onlineUsers = OnlineUser::all();
        $sortedUsers = OnlineUser::sortByCallPriority($onlineUsers, $callType);
    
        // Expecting the order: $internalAgent1, $internalAgent2, $user1, $user2, $user3
        $this->assertEquals($internalAgent1->id, $sortedUsers[0]->user_id);
        $this->assertEquals($internalAgent2->id, $sortedUsers[1]->user_id);
        $this->assertEquals($user1->id, $sortedUsers[2]->user_id);
        $this->assertEquals($user2->id, $sortedUsers[3]->user_id);
        $this->assertEquals($user3->id, $sortedUsers[4]->user_id);
    }
    

}