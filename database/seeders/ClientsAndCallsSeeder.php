<?php

namespace Database\Seeders;

use App\Models\Call;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientsAndCallsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     */
    public function run(): void
    {
        $specificUserId = User::whereEmail('testUser@example.com')->firstOrFail()->id;

        $numberOfClientsAndCalls = random_int(20, 80); // This will generate a random number of calls between 20 and 80

        Call::factory($numberOfClientsAndCalls)
            ->create(['user_id' => $specificUserId])
            ->each(function ($call) use ($specificUserId) {
                // Assume you want approximately 70% of calls to have a client
                if (random_int(1, 100) <= 70) {
                    $client = Client::factory()->make(['user_id' => $specificUserId]);
                    $call->client()->save($client);
                }
            });
    }
}
