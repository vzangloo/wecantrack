<?php

namespace WeCanTrack\Response;

use WeCanTrack\API\Request;
use WeCanTrack\Helper\Curl;

class ArrayResponse extends Response implements \Iterator
{
    private Request $query;
    private array $body = [];
    private $key;
    private $value;

    public function __construct(Request $query)
    {
        $this->query = $query;
    }

    protected function requestData()
    {
        $response = Curl::request($this->query->getHeaders())
            ->query($this->query->getPayloads())
            ->get($this->query->getUrl());
        if($response == false) {
            $this->error(Curl::getError());
        } else {
            $this->body = $response;
        }
    }

    public function getCount(): int
    {
        if(empty($this->body)) {
            $this->requestData();
        }
        return count($this->body);
    }

    public function rewind(): void
    {
        if(empty($this->body)) {
            $this->requestData();
        }

        $this->value = current($this->body);
        $this->key = key($this->body);
    }

    public function valid(): bool
    {
        if($this->hasError()) {
            return false;
        }
        return !empty($this->value);
    }

    public function current()
    {
        return $this->value;
    }

    public function key()
    {
        return $this->key;
    }

    public function next(): void
    {
        next($this->body);
        $this->value = current($this->body);
        $this->key = key($this->body);
    }
}