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

        $policies = InternalAgentMyBusiness::all();
    
        if ($policies->isEmpty()) {
            $this->error('No policies found to export.');
            return;
        }
    
        $filePath = 'policies.csv';
        $file = fopen($filePath, 'w');
    
        // Get the attributes from the first model to set as CSV headers
        $headers = array_keys($policies->first()->getAttributes());
    
        // Write the headers to the CSV file
        fputcsv($file, $headers);
    
        // Write each policy's attributes to the CSV
        foreach ($policies as $policy) {
            fputcsv($file, $policy->getAttributes());
        }
    
        fclose($file);
    
        $this->info('Data exported successfully to ' . $filePath);    
    }
}
