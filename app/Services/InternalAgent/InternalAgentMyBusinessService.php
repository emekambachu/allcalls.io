<?php

namespace App\Services\InternalAgent;

use App\Models\InternalAgentMyBusiness;

class InternalAgentMyBusinessService
{
    public function internalAgentMyBusiness(): InternalAgentMyBusiness
    {
        return new InternalAgentMyBusiness();
    }

    public function internalAgentMyBusinessById(int $id)
    {
        return $this->internalAgentMyBusiness()->findOrFail($id);
    }

    public function internalAgentMyBusinessByAgentId(int $agentId)
    {
        return $this->internalAgentMyBusiness()->where('agent_id', $agentId);
    }
}
