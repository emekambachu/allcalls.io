<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    private $numberOfRecords = 125;
    private $userId = 39;

    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 0; $i < $this->numberOfRecords; $i++) {
            if ($this->userId) {
                // If a user_id is specified, create a client with that user_id
                Client::factory()->create([
                    'user_id' => $this->userId,
                ]);
            } else {
                // If no user_id is specified, create a client with a random user
                Client::factory()->create();
            }
        }
    }
}
