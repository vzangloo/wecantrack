<?php
require 'bootstrap.php';

use WeCanTrack\Helper\TestCase;
use WeCanTrack\API\Websites;

class WebsitesTest extends TestCase
{
    public function testWebsitesData(): void
    {
        $websites = (new Websites(API_KEY, false))->get();
        $count = 0;
        foreach($websites as $data) {
            ++$count;
            //$this->info(json_encode($data));
        }
        $this->assertEquals($websites->getCount(), $count, 'Compare count');
    }
}
