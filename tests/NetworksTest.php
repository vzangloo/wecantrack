<?php
require 'bootstrap.php';

use WeCanTrack\Helper\TestCase;
use WeCanTrack\API\{Networks, NetworkAccounts};

class NetworksTest extends TestCase
{
    public function testNetworksData(): void
    {
        $networks = (new Networks(API_KEY, false))->get();
        $count = 0;
        foreach($networks as $data) {
            ++$count;
            //$this->info(json_encode($data));
        }
        $this->assertEquals($networks->getCount(), $count, 'Compare count');
    }

    public function testNetworkAccountsData(): void
    {
        $accounts = (new NetworkAccounts(API_KEY, false))->get();
        $count = 0;
        foreach($accounts as $data) {
            ++$count;
            //$this->info(json_encode($data));
        }
        $this->assertEquals($accounts->getCount(), $count, 'Compare count');
        $this->assertTrue(is_string($accounts->getEarliestCreatedDate()), 'Get earliest created date');
    }
}
