<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\EquisDuplicateMail;
use App\Models\EquisDuplicate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $duplicates = EquisDuplicate::whereDate('created_at', today())->get();

        // foreach($duplicates as $duplicate) {
        //     Mail::to(['iamfaizahmed123@gmail.com', 'ryan@allcalls.io', 'vince@allcalls.io'])
        //         ->send(new EquisDuplicateMail($duplicate->first_name . " " . $duplicate->last_name, $duplicate->upline_code, $duplicate->email));
        // }

        // Mail::to(['iamfaizahmed123@gmail.com'])
        // ->send(new EquisDuplicateMail('FirstName' . " " . 'LastName', 'EF222171', 'email@example.com'));

        Log::debug('send-equis-duplicate-emails:emails-sent');
    }
}
