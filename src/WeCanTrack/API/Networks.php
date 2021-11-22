<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\ArrayResponse;

class Networks extends Request
{
    protected string $api = 'https://api.wecantrack.com/api/v2/networks';

    public function get(): ArrayResponse
    {
        return new ArrayResponse($this);
    }
}
