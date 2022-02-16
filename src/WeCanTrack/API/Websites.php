<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\ArrayResponse;
use WeCanTrack\Response\WebsitesResponse;

class Websites extends Request
{
    protected string $api = 'https://api.wecantrack.com/api/v1/websites';

    public function get(): ArrayResponse
    {
        return new WebsitesResponse($this);
    }
}
