<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\EquisDuplicate;
use Illuminate\Console\Command;
use App\Mail\EquisDuplicateMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendEquisDuplicateEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:send-equis-duplicate-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send out daily emails for equis duplicate leads.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::debug('send-equis-duplicate-emails:start');

        $duplicates = EquisDuplicate::whereDate('created_at', Carbon::yesterday())->get(['first_name', 'last_name', 'upline_code', 'email']);

        // Ensure the temporary directory exists
        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // Create a temporary file
        $tmpFilePath = tempnam($tempDir, 'users-') . '.csv';

        Log::debug('send-equis-duplicate-emails:tmp-file-path', ['tmpFilePath' => $tmpFilePath]);

        $file = fopen($tmpFilePath, 'w');

        // Optional: Add CSV headers
        fputcsv($file, ['First Name', 'Last Name', 'Upline', 'Email']);

        foreach ($duplicates as $duplicate) {
            fputcsv($file, $duplicate->toArray());
        }

        fclose($file);

        // Send the email with the attachment
        Mail::to(['ryan@allcalls.io', 'vince@allcalls.io', 'bizdev@equisfinancial.com'])
            ->cc('contracting@allcalls.io')
            ->send(new EquisDuplicateMail($tmpFilePath));

        Log::debug('send-equis-duplicate-emails:emails-sent');

        // Optionally, delete the temporary file after sending the email
        unlink($tmpFilePath);
    }
}
