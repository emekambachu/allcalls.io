<?php

namespace App\Console\Commands;

use App\Models\Call;
use App\Models\InternalAgentMyBusiness;
use Illuminate\Console\Command;

class LinkCallsToBusiness extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:link-calls-to-business';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link calls to associated businesses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $internalAgentMyBusinesses = InternalAgentMyBusiness::whereNotNull('call_id')->get();

        foreach ($internalAgentMyBusinesses as $internalAgentMyBusiness) {
            $call = Call::whereId($internalAgentMyBusiness->call_id)->first();

            if ($call) {
                // Update the policy_id column with the call's ID
                $call->policy_id = $internalAgentMyBusiness->id;
                $call->save();
            } else {
                $this->info('Failed to update call with business Id=>' . $internalAgentMyBusiness->id);
            }
        }
    }
}
