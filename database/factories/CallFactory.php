<?php

namespace Database\Factories;

use App\Models\Call;
use App\Models\User;
use App\Services\Base\BaseService;
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
            'call_taken' => $callTaken = $faker->dateTimeBetween('-30 days', 'now')
                ->setTime($faker->numberBetween(9, 17), $faker->numberBetween(0, 59), $faker->numberBetween(0, 59)),
            'call_duration_in_seconds' => $faker->numberBetween(240, 600),
            'hung_up_by' => $faker->randomElement(['Caller', 'Agent']),
            'amount_spent' => $faker->numberBetween(20, 60),
            'recording_url' => 'https://api.twilio.com/2010-04-01/Accounts/ACd8064f6845e251c3019b9cc28257fee3/Recordings/RE6b4692e0f34cf762a4b1de44ed8968b7',
            'call_type_id' => $faker->numberBetween(1, 5),
            'publisher_id' => BaseService::randomChar(34, '0123456789abcdefABCDEF'),
            'publisher_name' => $faker->randomElement(['Assurance', 'BLM', 'PolicyScout', 'AmeriQuote']),
            'cost' => $faker->randomFloat(2, 5, 85), // Generating a random number between
            'created_at' => $callTaken,
            'updated_at' => $callTaken
        ];
    }
}
