<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\WebsitesResponse;

class Websites extends Request
{
    protected string $api = 'https://api.wecantrack.com/api/v1/websites';

    public function get(): WebsitesResponse
    {
        return new WebsitesResponse($this);
    }
}
