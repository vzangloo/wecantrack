<?php

namespace WeCanTrack\Helper;

class Utilities
{
    public static function extractReference(string $text): ?string
    {
        preg_match('/((WCT|wct)[0-9]{12}[A-Za-z0-9]{1,5})/', trim($text), $matches);
        return empty($matches[0]) ? null : $matches[0];
    }
}
