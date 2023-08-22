<?php

namespace Database\Factories;

use App\Models\Call;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CallFactory extends Factory
{
    protected $model = Call::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('en_US');
        return [
            'user_id' =>  function() {
                return User::factory()->create()->id;
            },
            'call_taken' => $faker->dateTimeBetween('-30 days', 'now')->setTime($faker->numberBetween(9, 17), $faker->numberBetween(0, 59), $faker->numberBetween(0, 59)),
            'call_duration_in_seconds' => $faker->numberBetween(240, 600),
            'hung_up_by' => $faker->randomElement(['Caller', 'Agent']),
            'amount_spent' => $faker->numberBetween(20, 60), // Generating a random number between 20 to 60 as the amount spent per call
            'recording_url' => $faker->url,
            'call_type_id' => $faker->numberBetween(1, 5),
        ];
    }
}
