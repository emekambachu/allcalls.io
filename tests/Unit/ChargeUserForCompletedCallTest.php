<?php

namespace Tests\Unit;

use App\Models\Bid;
use Tests\TestCase;
use App\Models\User;
use App\Models\CallType;
use App\Events\CompletedCallEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChargeUserForCompletedCallTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function testHandle(): void
    {
        // Create a call type
        $callType = CallType::create([
            'type' => 'Auto Insurance',
        ]);

        // Create some users
        $buyer1 = User::factory()->create(['balance' => 100]);
        $buyer2 = User::factory()->create(['balance' => 100]);
        $buyer3 = User::factory()->create(['balance' => 100]);
        $buyer4 = User::factory()->create(['balance' => 100]);

        // Create some bids
        Bid::create([
            'user_id' => $buyer1->id,
            'call_type_id' => $callType->id,
            'amount' => 50,
        ]);

        Bid::create([
            'user_id' => $buyer2->id,
            'call_type_id' => $callType->id,
            'amount' => 40,
        ]);

        Bid::create([
            'user_id' => $buyer3->id,
            'call_type_id' => $callType->id,
            'amount' => 30,
        ]);

        Bid::create([
            'user_id' => $buyer4->id,
            'call_type_id' => $callType->id,
            'amount' => 30,
        ]);

        // Trigger the CompletedCallEvent for each user
        $uniqueCallId = 'some-unique-id'; // This should be generated dynamically in a real-world scenario

        event(new CompletedCallEvent($buyer1, $callType, $uniqueCallId));

        // Assertions for buyer1...
        $this->assertEquals(59, $buyer1->fresh()->balance); // As buyer1 should be charged $41 (1 more than the second highest bid of $40)

    }
}