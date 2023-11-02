<?php

namespace App\Console\Commands;

use App\Models\AvailableNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReleaseAvailableNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:release-available-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release all available numbers in the system so they can be reused again';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::debug('Running allcalls:release-available-numbers');

        AvailableNumber::query()->update([
            'user_id' => null,
            'from' => null,
            'call_type_id' => null,
        ]);
    }
}
