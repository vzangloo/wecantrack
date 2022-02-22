<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\NetworkAccountsResponse;

/**
 * Class NetworkAccounts
 * @package WeCanTrack\API
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class NetworkAccounts extends Request
{
    /**
     * @var string The API endpoint for this request.
     */
    protected string $api = 'https://api.wecantrack.com/api/v2/network_accounts';

    /**
     * Filter account by account ID
     *
     * @param array|int $ids The account ID or array of account IDs.
     * @return $this
     */
    public function ids($ids): self
    {
        $this->payloads['ids'] = is_array($ids)? $ids: [$ids];
        return $this;
    }

    /**
     * Get the response for this request.
     *
     * @return NetworkAccountsResponse
     */
    public function get(): NetworkAccountsResponse
    {
        return new NetworkAccountsResponse($this);
    }
}
