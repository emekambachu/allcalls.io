<?php

function getStateName($id) {
    $state = \App\Models\State::find($id);
    dd($state);
    return $state->full_name;
}
