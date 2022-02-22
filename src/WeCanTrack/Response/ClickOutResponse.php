<?php

namespace WeCanTrack\Response;

use WeCanTrack\Helper\Utilities;

/**
 * Class ClickOutResponse
 * @package WeCanTrack\Response
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class ClickOutResponse extends Response
{
    protected object $content;

    /**
     * Constructor.
     *
     * @param mixed $content The response content.
     * @param $errors
     */
    public function __construct($content, $errors)
    {
        if($errors) {
            $this->addError($errors);
        } else {
            $this->content = $content;
        }
    }

    /**
     * Get the affiliate URL.
     *
     * @return string|null The affiliate URL.
     */
    public function getAffiliateUrl(): ?string
    {
        return $this->content->affiliate_url ?? null;
    }

    /**
     * Get the WCT reference.
     *
     * @return string|null The WCT reference.
     */
    public function getReference(): ?string
    {
        if($url = $this->getAffiliateUrl()) {
            return Utilities::extractReference($url);
        }
        return null;
    }

}
