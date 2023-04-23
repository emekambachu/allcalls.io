<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\State;
use App\Models\CallType;
use App\Models\UserCallTypeState;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the callTypes() method.
     *
     * @return void
     */
    public function testCallTypes()
    {
        $user = User::factory()->create();
        $callTypeA = CallType::factory()->create(['type' => 'Type A']);
        $callTypeB = CallType::factory()->create(['type' => 'Type B']);

        $user->callTypes()->attach($callTypeA, ['state_id' => 1], 'users_call_type_state');
        $user->callTypes()->attach($callTypeA, ['state_id' => 2], 'users_call_type_state');
        $user->callTypes()->attach($callTypeB, ['state_id' => 1], 'users_call_type_state');

        $this->assertTrue($user->callTypes->contains($callTypeA));
        $this->assertTrue($user->callTypes->contains($callTypeB));
        $this->assertEquals(2, $user->callTypes()->where('id', $callTypeA->id)->count());
        $this->assertEquals(1, $user->callTypes()->where('id', $callTypeB->id)->count());

        // Test accessing the state ID pivot column
        $this->assertEquals([1, 2], $user->callTypes()->where('id', $callTypeA->id)->pluck('users_call_type_state.state_id')->toArray());
        $this->assertEquals([1], $user->callTypes()->where('id', $callTypeB->id)->pluck('users_call_type_state.state_id')->toArray());
    }

    /**
     * Test the states() method.
     *
     * @return void
     */
    public function testStates()
    {
        $user = User::factory()->create();
        $stateX = State::factory()->create(['name' => 'State X']);
        $stateY = State::factory()->create(['name' => 'State Y']);

        $callType = CallType::factory()->create();

        $userCallTypeState1 = UserCallTypeState::create([
            'user_id' => $user->id,
            'call_type_id' => $callType->id,
            'state_id' => $stateX->id,
        ]);
        $userCallTypeState2 = UserCallTypeState::create([
            'user_id' => $user->id,
            'call_type_id' => $callType->id,
            'state_id' => $stateY->id,
        ]);
        $userCallTypeState3 = UserCallTypeState::create([
            'user_id' => $user->id,
            'call_type_id' => $callType->id,
            'state_id' => $stateX->id,
        ]);

        $this->assertTrue($user->states->contains($stateX));
        $this->assertTrue($user->states->contains($stateY));
        $this->assertEquals(2, $user->states()->where('states.id', $stateX->id)->count());
        $this->assertEquals(1, $user->states()->where('states.id', $stateY->id)->count());

        // Test accessing the call type ID pivot column
        $this->assertEquals([1, 2], $user->states()->where('states.id', $stateX->id)->pluck('users_call_type_state.call_type_id')->toArray());
        $this->assertEquals([1], $user->states()->where('states.id', $stateY->id)->pluck('users_call_type_state.call_type_id')->toArray());
    }
}
