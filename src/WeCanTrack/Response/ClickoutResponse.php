<?php

namespace WeCanTrack\Response;

use WeCanTrack\Helper\Utilities;

class ClickoutResponse extends Response
{
    protected object $content;

    public function __construct($content, $errors)
    {
        if($errors) {
            $this->addError($errors);
        } else {
            $this->content = $content;
        }
    }

    public function getAffiliateUrl(): ?string
    {
        return $this->content->affiliate_url ?? null;
    }

    public function getReference(): ?string
    {
        if($url = $this->getAffiliateUrl()) {
            return Utilities::extractReference($url);
        }
        return null;
    }

}
