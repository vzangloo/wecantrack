<?php

namespace WeCanTrack\Response;

/**
 * Class WebsitesResponse
 * @package WeCanTrack\Response
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class WebsitesResponse extends ArrayResponse
{
    /**
     * Get a specific website by its ID.
     *
     * @param int $id The website ID.
     * @return array The website data.
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
