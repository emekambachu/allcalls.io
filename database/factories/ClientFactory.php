<?php

namespace Database\Factories;

use App\Models\Call;
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
            'dob' => $faker->dateTimeBetween('-66 years', '-23 years')->format('Y-m-d'), // Generating dob for people aged between 23 and 66 years
            'user_id' => function() {
                return User::factory()->create()->id;
            },
            'call_id' => function() {
                return Call::factory()->create()->id;
            },
            'status' => $faker->randomElement([
                'Sale - Simplified Issue',
                'Sale - Guaranteed Issue',
                'Follow Up Needed',
                'Quoted - Not Interested',
                'Not Interested',
                'Transfer Handoff Too Long',
                'Client Hung Up',
                'No Income',
                'Wrong State',
                'Not Qualified Age',
                'Not Qualified Nursing Home',
                'Not Qualified Memory Issues',
                'Language Barrier',
                'Do Not Call'
            ]),
            'state' => $faker->state(),
        ];
    }
}
