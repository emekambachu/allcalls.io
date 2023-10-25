<?php

const PROGRESS_STATUSES = [
    'Invite sent',
    'Started contracting',
    'Completed and uploaded documents',
    'Onboarding documents reviewed',
    'Carrier contracts sent',
    'Agent signed',
];



function getStateName($id)
{

    $state = \App\Models\State::find($id);
    return $state->full_name;

}
