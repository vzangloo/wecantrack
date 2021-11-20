<?php
require 'bootstrap.php';

use WeCanTrack\Helper\TestCase;
use WeCanTrack\API\Clickout;

class ClickoutTest extends TestCase
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
    public function testClickout(string $url, bool $expected): void
    {
        $content = (new Clickout(API_KEY))
                    ->affiliateUrl($url)
                    ->clickoutUrl('https://localhost')
                    //->ipAddress('127.0.0.1')
                    ->metadata([
                        'test' => 'test',
                    ])
                    ->get();

        if($content->isValid()) {
            //echo json_encode($content->getErrors());
        }

    }
}