<?php

namespace WeCanTrack\Helper;

use PHPUnit\Framework\TestCase as Test;

class TestCase extends Test
{
    protected function info(string $message)
    {
        fwrite(STDOUT, $message . "\n");
    }
}