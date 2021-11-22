<?php

namespace WeCanTrack\Response;

use Carbon\Carbon;

class NetworkAccountsResponse extends ArrayResponse
{
    public function getEarliestCreatedDate(string $format = 'Y-m-d H:i:s'): ?string
    {
        $this->requestData();
        if($dates = array_filter(array_column($this->body, 'created_at'))){
            sort($dates);
            return Carbon::parse(array_shift($dates))->format($format);
        }
        return null;
    }

    public function findById(int $id): array
    {
        $this->requestData();
        if($key = array_search($id, array_column($this->body, 'id'))){
            return $this->body[$key];
        }
        return [];
    }

}
