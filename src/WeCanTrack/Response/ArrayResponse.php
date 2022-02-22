<?php

namespace WeCanTrack\Response;

use WeCanTrack\API\Request;
use WeCanTrack\Helper\Curl;

/**
 * Class ArrayResponse
 * @package WeCanTrack\Response
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class ArrayResponse extends Response implements \Iterator
{
    /**
     * @var Request The quest query.
     */
    private Request $query;

    /**
     * @var array The response body
     */
    protected array $body = [];

    /**
     * @var mixed The current Item key
     */
    private $key;

    /**
     * @var mixed The current Item value
     */
    private $value;

    /**
     * Constructor.
     *
     * @param Request $query The query that was used to make the request
     */
    public function __construct(Request $query)
    {
        $this->query = $query;
    }

    /**
     * Send the request and return the response
     *
     * @return void
     */
    protected function requestData()
    {
        if(empty($this->body)) {
            $response = Curl::request($this->query->getHeaders())
                ->query($this->query->getPayloads())
                ->get($this->query->getUrl());
            if(!$response) {
                $this->addError(Curl::getError());
            } else {
                $this->body = $response;
            }
        }
    }

    /**
     * Get the total number of items in the response
     *
     * @return int The number of items.
     */
    public function getCount(): int
    {
        $this->requestData();
        return count($this->body);
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->requestData();
        $this->value = current($this->body);
        $this->key = key($this->body);
    }

    /**
     * Has valid item.
     *
     * @return bool True if there is a valid item.
     */
    public function valid(): bool
    {
        if($this->hasError()) {
            return false;
        }
        return !empty($this->value);
    }

    /**
     * Get the current item value.
     *
     * @return mixed|null
     */
    public function current()
    {
        return $this->value;
    }

    /**
     * Get the current key.
     *
     * @return mixed|null
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Move to next item
     *
     * @return void
     */
    public function next(): void
    {
        next($this->body);
        $this->value = current($this->body);
        $this->key = key($this->body);
    }
}
