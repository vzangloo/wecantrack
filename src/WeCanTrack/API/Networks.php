<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\ArrayResponse;

/**
 * Class Networks
 * @package WeCanTrack\API
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class Networks extends Request
{
    /**
     * @var string The endpoint for this request.
     */
    protected string $api = 'https://api.wecantrack.com/api/v2/networks';

    /**
     * Get all networks data.
     *
     * @return ArrayResponse
     */
    public function get(): ArrayResponse
    {
        return new ArrayResponse($this);
    }
}
