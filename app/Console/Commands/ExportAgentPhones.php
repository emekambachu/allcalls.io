<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ExportAgentPhones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:export-agent-phones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export the phone numbers of all agents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $usersWithRole = User::whereHas('roles', fn ($query) => $query->where('name', 'internal-agent'))
            ->pluck('phone');

        foreach ($usersWithRole as $phone) {
            $this->line($phone);
        }
    }
}
