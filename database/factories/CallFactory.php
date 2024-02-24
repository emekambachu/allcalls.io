<?php

namespace Database\Factories;

use App\Models\Call;
use App\Models\User;
use App\Services\Base\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CallFactory extends Factory
{
    protected $model = Call::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('en_US');
        // Generate callTaken date-time within working hours
        $callTaken = $this->faker->dateTimeBetween('-60 days', 'now');
        $callTaken = Carbon::createFromTimestamp($callTaken->getTimestamp())
            ->setTime($this->faker->numberBetween(9, 17), $this->faker->numberBetween(0, 59), $this->faker->numberBetween(0, 59));

        return [
            'user_id' =>  function() {
                return User::factory()->create()->id;
            },
            'call_taken' => $callTaken,
            'call_duration_in_seconds' => $faker->numberBetween(240, 600),
            'hung_up_by' => $faker->randomElement(['Caller', 'Agent']),
            'amount_spent' => $faker->numberBetween(20, 60),
            'recording_url' => 'https://api.twilio.com/2010-04-01/Accounts/ACd8064f6845e251c3019b9cc28257fee3/Recordings/RE6b4692e0f34cf762a4b1de44ed8968b7',
            'call_type_id' => $faker->numberBetween(1, 5),
            'publisher_id' => BaseService::randomChar(34, '0123456789abcdefABCDEF'),
            'publisher_name' => $faker->randomElement(['Assurance', 'BLM', 'PolicyScout', 'AmeriQuote']),
            'cost' => $faker->randomFloat(2, 5, 85), // Generating a random number between
            'user_response_time' => $callTaken->copy()->addSeconds($faker->numberBetween(0, 20))->format('Y-m-d H:i:s'),
            'created_at' => $callTaken,
            'updated_at' => $callTaken
        ];
    }
}
