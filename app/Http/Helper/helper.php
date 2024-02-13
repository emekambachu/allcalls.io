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
const EQUIS_JOB_ERROR_EMAILS = ['awaisamir23@gmail.com', 'iamfaizahmed123@gmail.com'];
const TRAINING = 'Training';
const LIVE = 'Live';

function systemEmails() {
    $currentUrl = trim(url()->current());

    if (str_contains($currentUrl, 'staging.allcalls.io')) {
        return ['awaisamir23@gmail.com'];
    } else {
        return ['contracting@allcalls.io', 'maria@allcalls.io', 'ema@allcalls.io'];
    }
}

function recruitingEmail() {
    $currentUrl = trim(url()->current());
    if (str_contains($currentUrl, 'staging.allcalls.io')) {
        return ['awaisamir23@gmail.com'];
    } else {
        return ['recruiting@allcalls.io'];
    }
}

function getStateName($id)
{
    $state = \App\Models\State::find($id);
    return $state->full_name;
}


function getInviteeIds($parent)
{
    $ids = [$parent->id];
    foreach ($parent->invitees as $child) {
        $ids = array_merge($ids, getInviteeIds($child));
    }

    return $ids;
}
