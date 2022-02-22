<?php

namespace WeCanTrack\Response;

use Carbon\Carbon;

/**
 * Class NetworkAccountsResponse
 * @package WeCanTrack\Response
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class NetworkAccountsResponse extends ArrayResponse
{
    /**
     * Get the earliest created date among all the accounts.
     *
     * @param string $format The date format.
     * @return string|null The earliest created date.
     */
    public function getEarliestCreatedDate(string $format = 'Y-m-d H:i:s'): ?string
    {
        $this->requestData();
        if($dates = array_filter(array_column($this->body, 'created_at'))){
            sort($dates);
            return Carbon::parse(array_shift($dates))->format($format);
        }
        return null;
    }

    /**
     * Get the account data by the account id.
     *
     * @param int $id The account ID.
     * @return array The array of account data.
     */
    public function findById(int $id): array
    {
        $this->requestData();
        if($key = array_search($id, array_column($this->body, 'id'))){
            return $this->body[$key];
        }
        return [];
    }

}
