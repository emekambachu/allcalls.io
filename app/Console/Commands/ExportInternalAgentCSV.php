<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ExportInternalAgentCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:export-internal-agents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all internal agents to a CSV file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Exporting data to CSV...');

        $users = User::whereHas('roles', fn ($query) => $query->where('name', 'internal-agent'))
            ->get(['id', 'first_name', 'last_name', 'email', 'phone']);

        $filePath = 'users.csv';
        $file = fopen($filePath, 'w');

        // Optional: Add CSV headers
        fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Phone']);

        foreach ($users as $user) {
            fputcsv($file, $user->toArray());
        }

        fclose($file);

        $this->info('Data exported successfully to ' . $filePath);

    }
}
