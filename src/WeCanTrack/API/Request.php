<?php

namespace WeCanTrack\API;

use WeCanTrack\Traits\Error;

/**
 * Class Request
 * @package WeCanTrack\API
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2021 Saleduck Asia Sdn Bhd
 */
abstract class Request
{
    use Error;

    protected string $api = '';
    protected array $headers = [];
    protected array $payloads = [];

    /**
     * WeCanTrack constructor.
     * @param string $apiKey
     */
    final public function __construct(string $apiKey, bool $headerKey = true)
    {
        $this->headers = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if($headerKey) {
            $this->headers['headers']['X-API-Key'] = $apiKey;
        } else {
            $this->payloads['api_key'] = $apiKey;
        }
    }

    public function getUrl(): string
    {
        return $this->payloads
            ? $this->api . '?' . http_build_query($this->payloads)
            : $this->api;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPayloads(): array
    {
        return $this->payloads;
    }

}