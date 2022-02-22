<?php

namespace WeCanTrack\Helper;

use PHPUnit\Framework\TestCase as Test;

/**
 * Class TestCase
 * @package WeCanTrack\Helper
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class TestCase extends Test
{
    /**
     * Display info message
     *
     * @param string $message The message to be displayed.
     * @return void
     */
    protected function info(string $message)
    {
        fwrite(STDOUT, $message . "\n");
    }
}
