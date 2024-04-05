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
            // Check if all expected columns are present
            if (count($row) >= 5) {
                list($userName, $firstName, $lastName, $email, $phoneNumber) = $row;

                // Your existing logic here...
                $this->info('Match found: ' . $userName . ' for ' . $email . ' or ' . $phoneNumber);
            } else {
                $this->error('A row in the CSV file does not contain all required columns.');
            }
        }

        fclose($fileHandle);

        $this->info('UserNames have been imported successfully.');
    }
}
