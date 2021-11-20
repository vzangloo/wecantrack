<?php

namespace WeCanTrack\Response;

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
            preg_match('/((WCT|wct)[0-9]{12}[A-Za-z0-9]{1,5})/', $url, $matches);
            return empty($matches[0]) ? null : $matches[0];
        }
        return null;
    }

}