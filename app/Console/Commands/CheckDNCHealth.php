<?php

namespace App\Console\Commands;

use App\Models\DeltaExecution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckDNCHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:check-dnc-health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the status of the DNC merge for the day and sends an email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // First get all App\Models\DeltaExecution records for today in EST timezone:
        $today = now()->setTimezone('America/New_York')->format('Y-m-d');
        $deltaExecutions = DeltaExecution::whereDate('created_at', $today)->get();

        // deltaExecution could have two statuses: 'Successful' or 'Unsuccessful'
        // Check if ANY of them are 'Successful':
        $successful = $deltaExecutions->contains('status', 'Successful');

        if ($successful) {
            // $this->info('Sending success email to the team...');

            // Send a plain text email to the email "iamfaizahmed123@gmail.com"
            // with the subject "DNC Merge Successful" and the body "The DNC merge was successful."
            Mail::send([], [], function ($message) {
                $message->to('iamfaizahmed123@gmail.com');
                $message->subject('DNC Merge Successful');
                $message->setBody('The DNC merge was successful.', 'text/plain');
            });
        } else {
            $this->error('Sending failed email to the team...');
        }
    }
}
