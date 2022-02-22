<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\WebsitesResponse;

/**
 * Class Websites
 * @package WeCanTrack\API
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class Websites extends Request
{
    /**
     * @var string The API endpoint for this request.
     */
    protected string $api = 'https://api.wecantrack.com/api/v1/websites';

    /**
     * Get the list of websites data
     *
     * @return WebsitesResponse The website response object.
     */
    public function get(): WebsitesResponse
    {
        return new WebsitesResponse($this);
    }
}
