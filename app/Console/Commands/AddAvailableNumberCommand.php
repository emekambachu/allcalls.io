<?php

namespace App\Console\Commands;

use App\Models\AvailableNumber;
use Illuminate\Console\Command;

class AddAvailableNumberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:add-available-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add available number';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get phone from user input
        $phone = $this->ask('What is the phone number?');

        // Create new call type number
        AvailableNumber::create([
            'phone' => $phone,
            'user_id' => null,
        ]);

        $this->info('Available number added successfully!');

        return 0;
    }
}