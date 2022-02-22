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

    /**
     * @var string API endpoint
     */
    protected string $api = '';

    /**
     * @var array The request headers
     */
    protected array $headers = [];

    /**
     * @var array The request parameters
     */
    protected array $payloads = [];

    /**
     * WeCanTrack constructor.
     *
     * @param string $apiKey    The API key to use for the request.
     * @param bool $headerKey   Whether include API key in header, else API key will be included in payload. Default: true
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

    /**
     * Get the full API endpoint.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->payloads
            ? $this->api . '?' . http_build_query($this->payloads)
            : $this->api;
    }

    /**
     * Get API headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get API payloads
     *
     * @return array
     */
    public function getPayloads(): array
    {
        return $this->payloads;
    }

}
