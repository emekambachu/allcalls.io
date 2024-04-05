<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportEFNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:import-ef-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the ef numbers for all ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = __DIR__ . '/AgentsAndEFNumbers.csv';

        $this->info('Reading from: ' . $filePath);

        // Open the file
        $fileHandle = fopen($filePath, 'r');

        // Skip the header row
        fgetcsv($fileHandle);

        // Iterate through the CSV rows
        while (($row = fgetcsv($fileHandle)) !== false) {
            list($userName, $firstName, $lastName, $email, $phoneNumber) = $row;

            // Search for a user by email or phone number and update equis_id
            // \App\Models\User::where('email', $email)
            //     ->orWhere('phone_number', $phoneNumber) // Adjust your column name for phone number
            //     ->update(['equis_id' => $userName]);
            $this->info('Match found: ' . $userName . ' for ' . $email . ' or ' . $phoneNumber);
        }

        fclose($fileHandle);

        $this->info('UserNames have been imported successfully.');
    }
}
