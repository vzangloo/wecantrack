<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\NetworkAccountsResponse;

class NetworkAccounts extends Request
{
    protected string $api = 'https://api.wecantrack.com/api/v2/network_accounts';

    public function ids($ids): self
    {
        $this->payloads['ids'] = is_array($ids)? $ids: [$ids];
        return $this;
    }

    public function get(): NetworkAccountsResponse
    {
        return new NetworkAccountsResponse($this);
    }
}
