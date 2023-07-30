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

        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'phone' => $faker->phoneNumber,
            'zipCode' => $faker->postcode,
            'email' => $faker->unique()->safeEmail,
            'address' => $faker->address,
            'dob' => $faker->dateTimeBetween('-66 years', '-23 years')->format('Y-m-d'), // Generating dob for people aged between 23 to 66 years
            'call_taken' => $faker->dateTimeBetween('-30 days', 'now')->setTime($faker->numberBetween(9, 17), $faker->numberBetween(0, 59), $faker->numberBetween(0, 59)),
            'call_duration_in_seconds' => $callDurationInSeconds,
            'hung_up_by' => $faker->randomElement(['Caller', 'Agent']),
            'amount_spent' => $faker->numberBetween(20, 60), // Generating a random number between 20 to 60 as the amount spent per call
            'call_id' => $faker->uuid,
            'call_type_id' => $faker->numberBetween(1, 6),
            'user_id' => function () {
                return User::factory()->create()->id;
            }
        ];
    }
}
