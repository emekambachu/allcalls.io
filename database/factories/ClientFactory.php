<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        // Create a new user for each client
        $user = User::factory()->create();

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'zipCode' => $this->faker->postcode,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'dob' => $this->faker->date('Y-m-d', 'now'),
            'call_taken' => now(),
            'call_duration_in_seconds' => $this->faker->numberBetween(1, 3600),
            'hung_up_by' => $this->faker->randomElement(['Caller', 'Agent']),
            'amount_spent' => $this->faker->randomFloat(2, 0, 999999.99),
            'call_id' => $this->faker->uuid,
            'call_type_id' => $this->faker->numberBetween(1, 6),
            'user_id' => $user->id,
        ];
    }
}
