<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\ClickOutResponse;
use WeCanTrack\Helper\Curl;

class ClickOut extends Request
{
    protected string $api = 'https://api.wecantrack.com/api/v1/clickout';

    public function affiliateUrl(string $url, bool $encode = true): self
    {
        $this->payloads['affiliate_url'] = $encode ? urlencode($url) : $url;
        return $this;
    }

    public function clickoutUrl(string $url): self
    {
        $this->payloads['clickout_url'] = $url;
        return $this;
    }

    public function redirectUrl(string $url): self
    {
        $this->payloads['redirect_url'] = $url;
        return $this;
    }

    public function metadata(array $data = []): self
    {
        if ($data && is_array($data)) {
            $this->payloads['metadata'] = $data;
        }
        return $this;
    }

    public function customIndex(string $value, int $index = 1): self
    {
        if (1 <= $index && $index <= 5) {
            $this->payloads["custom_index_{$index}"] = $value;
        }
        return $this;
    }

    public function useClickReference(string $reference): self
    {
        $this->payloads['user_click_reference'] = $reference;
        return $this;
    }

    /**
     * Set IP address
     *
     * This is a hidden param
     *
     * @param string $ip
     * @return $this
     */
    public function ipAddress(string $ip): self
    {
        $this->payloads['ip'] = $ip;
        return $this;
    }

    public function get(): ClickOutResponse
    {
        $this->payloads['_ga'] = $_COOKIE['_ga'] ?? null;
        $this->payloads['_wctrck'] = $_COOKIE['_wctrck'] ?? null;

        $response = Curl::request($this->getHeaders())
                        ->query($this->getPayloads())
                        ->decodeBody(false)
                        ->post($this->getUrl());
        return new ClickOutResponse($response, Curl::getError());
    }
}
