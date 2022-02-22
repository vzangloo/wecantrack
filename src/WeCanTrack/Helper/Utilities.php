<?php

namespace WeCanTrack\Helper;

/**
 * Class Utilities
 * @package WeCanTrack\Helper
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class Utilities
{
    /**
     * Extract WCT reference from the given URL/ Text.
     *
     * @param string $text The URL/ Text that might contain WCT reference.
     * @return string|null The WCT reference if found, null otherwise.
     */
    public static function extractReference(string $text): ?string
    {
        preg_match('/((WCT|wct)[0-9]{12}[A-Za-z0-9]{1,5})/', trim($text), $matches);
        return empty($matches[0]) ? null : $matches[0];
    }
}
