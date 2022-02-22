<?php

namespace WeCanTrack\API;

use WeCanTrack\Response\ClickOutResponse;
use WeCanTrack\Helper\Curl;

/**
 * Class ClickOut
 * @package WeCanTrack\API
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class ClickOut extends Request
{
    /**
     * @var string The API endpoint for ClickOut
     */
    protected string $api = 'https://api.wecantrack.com/api/v1/clickout';

    /**
     * Add the Affiliate URL to be tracked.
     *
     * @param string $url The affiliate URL to be tracked.
     * @param bool $encode Whether to encode the URL. Default: true.
     * @return $this
     */
    public function affiliateUrl(string $url, bool $encode = true): self
    {
        $this->payloads['affiliate_url'] = $encode ? urlencode($url) : $url;
        return $this;
    }

    /**
     * Add the user click out URL.
     *
     * @param string $url The click out URL.
     * @return $this
     */
    public function clickoutUrl(string $url): self
    {
        $this->payloads['clickout_url'] = $url;
        return $this;
    }

    /**
     * Add the redirect URL.
     *
     * @param string $url The redirect URL.
     * @return $this
     */
    public function redirectUrl(string $url): self
    {
        $this->payloads['redirect_url'] = $url;
        return $this;
    }

    /**
     * Add custom data attached to teh click out.
     *
     * @param array $data The custom data.
     * @return $this
     */
    public function metadata(array $data = []): self
    {
        if ($data && is_array($data)) {
            $this->payloads['metadata'] = $data;
        }
        return $this;
    }

    /**
     * Add custom index to the click out.
     *
     * @param string $value The custom index value.
     * @param int $index The custom index key. Range from 1 to 5. Default: 1.
     * @return $this
     */
    public function customIndex(string $value, int $index = 1): self
    {
        if (1 <= $index && $index <= 5) {
            $this->payloads["custom_index_{$index}"] = $value;
        }
        return $this;
    }

    /**
     * Add click reference to the click out.
     *
     * @param string $reference The click reference.
     * @return $this
     */
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
     * @param string $ip The IP address.
     * @return $this
     */
    public function ipAddress(string $ip): self
    {
        $this->payloads['ip'] = $ip;
        return $this;
    }

    /**
     * Send click out request.
     *
     * @return ClickOutResponse The click out response.
     */
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
