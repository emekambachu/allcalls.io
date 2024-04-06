<?php

namespace App\Console\Commands;

use App\Models\User;
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
    protected $description = 'Imports the ef numbers for all equis agents.';

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

        $updatedCount = 0; // Counter for the updated records

        // Iterate through the CSV rows
        while (($row = fgetcsv($fileHandle)) !== false) {
            // Check if all expected columns are present
            if (count($row) >= 5) {
                list($userName, $firstName, $lastName, $email, $phoneNumber) = $row;

                // Update and count affected rows
                $affectedRows = User::where('email', $email)
                    ->orWhere('phone', $phoneNumber)
                    ->update(['equis_number' => $userName]);

                if ($affectedRows > 0) {
                    $this->info('Match found and updated: ' . $userName . ' for ' . $email . ' or ' . $phoneNumber);
                    $updatedCount += $affectedRows; // Increment the counter by the number of affected rows
                }
            } else {
                $this->error('A row in the CSV file does not contain all required columns.');
            }
        }

        fclose($fileHandle);

        // Print the total number of updated records
        $this->info($updatedCount . ' records have been updated successfully.');
    }
}
