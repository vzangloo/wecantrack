<?php
require 'bootstrap.php';

use WeCanTrack\Helper\TestCase;
use WeCanTrack\API\{Networks, NetworkAccounts};

class NetworksTest extends TestCase
{
    public function testNetworksData(): void
    {
        $response = (new Networks(API_KEY, false))->get();
        $count = 0;
        foreach($response as $data) {
            ++$count;
            //$this->info(json_encode($data));
        }
        $this->assertEquals($response->getCount(), $count, 'Compare count');
    }

    public function testNetworkAccountsData(): void
    {
        $response = (new NetworkAccounts(API_KEY, false))->get();
        $count = 0;
        foreach($response as $data) {
            ++$count;
            //$this->info(json_encode($data));
        }
        $this->assertEquals($response->getCount(), $count, 'Compare count');
    }
}