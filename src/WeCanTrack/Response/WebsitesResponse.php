<?php

namespace WeCanTrack\Response;

use Carbon\Carbon;

class WebsitesResponse extends ArrayResponse
{
    public function findById(int $id): array
    {
        $this->requestData();
        if($key = array_search($id, array_column($this->body, 'id'))){
            return $this->body[$key];
        }
        return [];
    }

}
