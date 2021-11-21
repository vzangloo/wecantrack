<?php

namespace WeCanTrack\Response;

use WeCanTrack\API\Request;
use WeCanTrack\Helper\Curl;

class ArrayResponse extends Response implements \Iterator
{
    private Request $query;
    protected array $body = [];
    private $key;
    private $value;

    public function __construct(Request $query)
    {
        $this->query = $query;
    }

    protected function requestData()
    {
        if(empty($this->body)) {
            $response = Curl::request($this->query->getHeaders())
                ->query($this->query->getPayloads())
                ->get($this->query->getUrl());
            if($response == false) {
                $this->addError(Curl::getError());
            } else {
                $this->body = $response;
            }
        }
    }

    public function getCount(): int
    {
        $this->requestData();
        return count($this->body);
    }

    public function rewind(): void
    {
        $this->requestData();
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
