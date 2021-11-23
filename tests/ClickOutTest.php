<?php
require 'bootstrap.php';

use WeCanTrack\Helper\TestCase;
use WeCanTrack\API\ClickOut;

class ClickOutTest extends TestCase
{
    public function urlProvider(): array
    {
        return [
            'valid url' => ['https://www.awin1.com/cread.php?awinmid=10921&awinaffid=211395&clickref2=MY&ued=https%3A%2F%2Fwww.bookdepository.com%2FIt-Ends-With-Us-most-heartbreaking-novel-youll-ever-read-COLLEEN-HOOVER%2F9781471156267%3Fref%3Dgrid-view', true],
            //'invalid url' => ['https://www.google.com', false],
            //'invalid url2' => ['https://sephoramalaysia.sjv.io/b3PM2M', false],
        ];
    }

    /**
     * @dataProvider urlProvider
     *
     * @param string $url
     * @param bool $expected
     * @return void
     */
    public function testClickOut(string $url, bool $expected): void
    {
        $clickOut = (new ClickOut(API_KEY))
                    ->affiliateUrl($url)
                    ->clickoutUrl('https://localhost')
                    //->ipAddress('127.0.0.1')
                    ->metadata([
                        'test' => 'test',
                    ])
                    ->get();

        if($clickOut->isValid()) {
            $this->assertTrue(str_contains($clickOut->getAffiliateUrl(), 'wct'), 'Affiliate URL contains WCT reference');
            $this->assertTrue(str_contains($clickOut->getReference(), 'wct'), 'Valid WCT reference');
        } else {
            $error = json_encode($clickOut->getErrors());
            $this->assertIsString($error, 'ClickOut able to return error');
        }
    }
}
