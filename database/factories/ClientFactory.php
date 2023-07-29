<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('en_US');
        $callDurationInSeconds = $faker->numberBetween(240, 600);
        $ratePerMinute = 50; // Rate of $50 per minute

        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'phone' => $faker->phoneNumber,
            'zipCode' => $faker->postcode,
            'email' => $faker->unique()->safeEmail,
            'address' => $faker->address,
            'dob' => $faker->dateTimeBetween('-66 years', '-23 years')->format('Y-m-d'), // Generating dob for people aged between 23 to 66 years
            'call_taken' => now(),
            'call_duration_in_seconds' => $callDurationInSeconds,
            'hung_up_by' => $faker->randomElement(['Caller', 'Agent']),
            'amount_spent' => ($callDurationInSeconds / 60) * $ratePerMinute, // Calculating the amount spent based on the call duration and rate per minute
            'call_id' => $faker->uuid,
            'call_type_id' => $faker->numberBetween(1, 6),
            'user_id' => User::factory(), // assuming User model is related to Client model
        ];
    }
}
