<?php

use Carbon\Carbon;

const PROGRESS_STATUSES = [
    //    'Invite sent',
    //    'Started contracting',
    //    'Completed and uploaded documents',
    //    'Onboarding documents reviewed',
    //    'Carrier contracts sent',
    //    'Agent signed',
    'Missing Information',
    'Needs ICA',
    'Contracting Reviewing Documents',
    'Contracting Missing AML',
    'Contracting Missing Resident License',
    'Contracting Missing Banking Info',
    'Carrier Contracts Sent to Agent',
    'Agent Signed Carrier Contracts',
    'Contracts Sent to Carrier',
];

function systemEmails() {
    $currentUrl = trim(url()->current());

    if (str_contains($currentUrl, 'staging.allcalls.io')) {
        return ['awaisamir23@gmail.com'];
    } else {
        return ['contracting@allcalls.io', 'maria@allcalls.io', 'kat@allcalls.io'];
    }
}

function getStateName($id)
{
    $state = \App\Models\State::find($id);
    return $state->full_name;
}
