<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'type' => "App\Notifications\ZoomMeeting",
            'notifiable_type' => "App\Models\User",
            'notifiable_id' => Auth::user() ? Auth::user()->id : 1,
            'data' =>  '{"title":"A LOCAL TECHNICAL TR TEST 2","body":"IGNORE THIS","zoomLink":null}',
            'read_at' => null, // leave null for unread and set to current timestamp for read
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
