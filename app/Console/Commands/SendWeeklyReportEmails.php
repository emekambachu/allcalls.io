<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\WeeklyReportEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
        $totalAgentsCount = User::whereHas('roles', function ($query) {
            $query->where('name', 'internal-agent');
        })->where('created_at', '>=', now()->subDays(7))->count();
        $approvedAgentsCount = User::whereHas('roles', function ($query) {
            $query->where('name', 'internal-agent');
        })->where('created_at', '>=', now()->subDays(7))
            ->where('is_locked', false)
            ->count();

        Mail::to(['iamfaizahmed123@gmail.com'])->send(new WeeklyReportEmail($totalAgentsCount, $approvedAgentsCount));
    }
}
