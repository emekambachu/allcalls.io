<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendWeeklyReportEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:send-weekly-report-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly report emails to super admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $internalAgents = User::whereHas('roles', function ($query) {
            $query->where('name', 'internal-agent');
        })->where('created_at', '>=', now()->subDays(6)) // Adjust the number of days as needed
          ->get();
    }
}
