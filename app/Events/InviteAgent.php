<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InviteAgent
{
    use SerializesModels, Dispatchable;
    public $email;
    public $url;


    /**
     * Create a new event instance.
     */
    public function __construct($email, $url)
    {
        $this->email = $email;
        $this->url = $url;
    }
}
