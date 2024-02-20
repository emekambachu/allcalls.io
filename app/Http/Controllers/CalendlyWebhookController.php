<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendlyWebhookController extends Controller
{
    public function show(Request $request)
    {
        try {
            if($request['request']['payload']['status'] == 'active') {
                $userExist = User::whereEmail($request['request']['payload']['email'])->first();
                if($userExist) {
                    if($request['request']['payload']['scheduled_event']['name'] == 'New Agent Call Review') {
                        $userExist->new_agent_call_scheduled = true;
                        Log::debug('Low balance calendly meeting scheduled.');
                        $userExist->save();
                        return;
                    }
                    elseif($request['request']['payload']['scheduled_event']['name'] == 'New Agent Training'){
                        $userExist->low_balance_call_scheduled = true;
                        $userExist->save();
                        Log::debug('Low balance calendly meeting scheduled.');
                        return;
                    }
                }
            }

            if( $request['request']['payload']['status'] == 'canceled') {
                $userExist = User::whereEmail($request['request']['payload']['email'])->first();
                if($userExist) {
                    if($request['request']['payload']['scheduled_event']['name'] == 'New Agent Call Review') {
                        $userExist->new_agent_call_scheduled = false;
                        Log::debug('Low balance calendly meeting cancelled.');
                        $userExist->save();
                        return;
                    }
                    elseif($request['request']['payload']['scheduled_event']['name'] == 'New Agent Training'){
                        $userExist->low_balance_call_scheduled = false;
                        $userExist->save();
                        Log::debug('Low balance calendly meeting cancelled.');
                        return;
                    }
                }
            }

            Log::debug('Calendly not working. Current status is --->'.$request['request']['payload']['status']);
        }
        catch (\Exception $e) {
            Log::debug('CalendlyWebhook:', [
                'request' => $request->all(),
            ]);
            Log::debug('Calendly Webhook Exception---->'.$e->getMessage());
        }
    }
}
