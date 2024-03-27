<?php

namespace App\Console\Commands;

use App\Models\DeltaExecution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        Log::debug('Checking the status of the DNC merge...');

        // First get all App\Models\DeltaExecution records for today in EST timezone:
        $today = now()->setTimezone('America/New_York')->format('Y-m-d');
        $formattedToday = now()->setTimezone('America/New_York')->format('m-d-Y'); // Format date as mm-dd-yyyy
        $deltaExecutions = DeltaExecution::whereDate('created_at', $today)->get();

        // deltaExecution could have two statuses: 'Successful' or 'Unsuccessful'
        // Check if ANY of them are 'Successful':
        $successful = $deltaExecutions->contains('status', 'Successful');

        $recipients = ['iamfaizahmed123@gmail.com', 'ryan@allcalls.io', 'vince@allcalls.io'];

        if ($successful) {
            // Sending success email to multiple recipients:
            Mail::send([], [], function ($message) use ($formattedToday, $recipients) {
                $message->to($recipients); // Use the array of recipients
                $message->subject("✅ $formattedToday: DNC Merge Successful");
                $message->text("The DNC merge was successful for $formattedToday.");
            });
        } else {
            // Sending failed email to multiple recipients:
            Mail::send([], [], function ($message) use ($formattedToday, $recipients) {
                $message->to($recipients); // Use the array of recipients
                $message->subject("❌ $formattedToday: DNC Merge Unsuccessful");
                $message->text("The DNC merge was unsuccessful for $formattedToday.");
            });
        }
    }
}
