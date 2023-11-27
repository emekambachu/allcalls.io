<?php

namespace App\Console\Commands;

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

        // EquisDuplicate all records where created_at is today
        $duplicates = EquisDuplicate::whereDate('created_at', today())->get(['first_name', 'last_name', 'upline_code', 'email']);

        // Create a temporary file
        $tmpFilePath = Storage::path('temp/users-' . uniqid() . '.csv');

        Log::debug('send-equis-duplicate-emails:tmp-file-path', ['tmpFilePath' => $tmpFilePath]);

        $file = fopen($tmpFilePath, 'w');

        // Optional: Add CSV headers
        fputcsv($file, ['First Name', 'Last Name', 'Upline', 'Email']);

        foreach ($duplicates as $duplicate) {
            fputcsv($file, $duplicate->toArray());
        }

        fclose($file);

        // Send the email with the attachment
        Mail::to(['iamfaizahmed123@gmail.com'])
            ->send(new EquisDuplicateMail('FirstName' . " " . 'LastName', 'EF222171', 'email@example.com', $tmpFilePath));

        Log::debug('send-equis-duplicate-emails:emails-sent');

    }
}
