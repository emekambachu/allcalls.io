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

const IN_TRAINING_STATUS_ROUTES = ['take-calls.show', 'take-calls.online-users.store', 'take-calls.online-users.destroy', 'training.index', 'profile.view', 'upload.profile.picture', 'profile.edit', 'profile.update', 'profile.destroy'];
const TRAINING_MINIMUM_AMOUNT = 40;
const TRAINING = 'Training';
const LIVE = 'Live';
const NOT_LIVE = 'Not Live';


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
