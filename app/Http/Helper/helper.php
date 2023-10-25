<?php

function getStateName($id) {
    $state = \App\Models\State::find($id);
    return $state->full_name;
}
