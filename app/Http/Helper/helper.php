<?php

function getStateName($id) {
    try {
        $state = \App\Models\State::find($id);
        return $state->full_name;
    }
    catch (\Exception $e) {
        dd($id);
    }
}
