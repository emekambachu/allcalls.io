<?php

namespace App\Console\Commands;

use App\Models\CallType;
use App\Models\CallTypeNumber;
use Illuminate\Console\Command;

class AddCallTypeNumberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:add-call-type-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get phone from user input
        $phone = $this->ask('What is the phone number?');
        
        // Get all call types from the database
        $callTypes = CallType::all();
        
        if ($callTypes->isEmpty()) {
            $this->error('No call types available!');
            return 1;
        }
        
        // Prepare options for the choice question
        $callTypeOptions = $callTypes->pluck('type', 'id')->toArray();
        $selectedTypeKey = array_search($this->choice('Please choose a call type', $callTypeOptions), $callTypeOptions);
        
        // Create new call type number
        CallTypeNumber::create([
            'phone' => $phone,
            'call_type_id' => $selectedTypeKey,
        ]);
        
        $this->info('Call type number added successfully!');
            
        return 0;
    }
    
    
}
