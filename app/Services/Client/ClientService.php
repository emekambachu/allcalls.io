<?php

namespace App\Services\Client;

use App\Models\Client;

class ClientService
{
    public function client(): Client
    {
        return new Client();
    }

    public function clientWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->client()->with('user', 'call', 'policies');
    }
}
