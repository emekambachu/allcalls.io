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
    
        // Get the attributes from the first model to set as CSV headers
        $headers = array_keys($policies->first()->getAttributes());
        // Append the new headers for publisher name and ID
        array_push($headers, 'Publisher Name', 'Publisher ID');
    
        // Write the headers to the CSV file
        fputcsv($file, $headers);
    
        // Write each policy's attributes to the CSV
        foreach ($policies as $policy) {
            $attributes = $policy->getAttributes();
    
            // Append publisher name and ID if client_id is not null and related data exists
            if (!is_null($policy->client_id) && !is_null($policy->client) && !is_null($policy->client->call)) {
                $attributes['Publisher Name'] = $policy->client->call->publisher_name ?? '';
                $attributes['Publisher ID'] = $policy->client->call->publisher_id ?? '';
            } else {
                $attributes['Publisher Name'] = '';
                $attributes['Publisher ID'] = '';
            }
    
            fputcsv($file, $attributes);
        }
    
        fclose($file);
    
        $this->info('Data exported successfully to ' . $filePath);
    }
}
