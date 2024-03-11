<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InternalAgentMyBusiness;

class ExportPoliciesCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:export-policies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all policies to a CSV file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Exporting data to CSV...');

        $policies = InternalAgentMyBusiness::with('client.call')->get();

        if ($policies->isEmpty()) {
            $this->error('No policies found to export.');
            return;
        }

        $filePath = 'policies.csv';
        $file = fopen($filePath, 'w');

        // Define headers manually to include the extra columns
        $headers = ['Policy Headers', 'Publisher Name', 'Publisher ID']; // Replace 'Policy Headers' with your actual policy headers

        // Write the headers to the CSV file
        fputcsv($file, $headers);

        // Write each policy's attributes to the CSV
        foreach ($policies as $policy) {
            $attributes = $policy->getAttributes();

            // Check if there's a non-null client_id and related call data
            if (!is_null($policy->client_id) && !is_null($policy->client) && !is_null($policy->client->call)) {
                $attributes['publisher_name'] = $policy->client->call->publisher_name;
                $attributes['publisher_id'] = $policy->client->call->publisher_id;
            } else {
                $attributes['publisher_name'] = null;
                $attributes['publisher_id'] = null;
            }

            fputcsv($file, $attributes);
        }

        fclose($file);

        $this->info('Data exported successfully to ' . $filePath);
    }
}
