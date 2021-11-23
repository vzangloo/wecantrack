<?php
require 'bootstrap.php';

use WeCanTrack\Helper\TestCase;
use WeCanTrack\API\Transactions;

class TransactionsTest extends TestCase
{
    public function dateProvider(): array
    {
        return [
            'valid dates' => ['2021-09-01', '2021-09-2'],
        ];
    }

    public function dateLimitProvider(): array
    {
        return [
            'valid dates (limit 100)' => ['2021-09-01', '2021-09-2', 100],
            'valid dates (limit 300)' => ['2021-09-01', '2021-09-2', 300],
            'valid dates (limit 500)' => ['2021-09-01', '2021-09-2', 500],
        ];
    }

    public function datePageLimitProvider(): array
    {
        return [
            'valid dates (page 3, limit 100)' => ['2021-09-01', '2021-09-2', 3, 100],
            'valid dates (page 2, limit 200)' => ['2021-09-01', '2021-09-2', 2, 200],
            'valid dates (page 3, limit 300)' => ['2021-09-01', '2021-09-2', 3, 300],
            'valid dates (page 1, limit 500)' => ['2021-09-01', '2021-09-2', 1, 500],
        ];
    }

    /**
     * @dataProvider dateProvider
     *
     * @param string $startDate
     * @param string $endDate
     * @return void
     */
    public function testDataCount(string $startDate, string $endDate): void
    {
        $response = (new Transactions(API_KEY))->get($startDate, $endDate);
        $count = 0;
        foreach($response as $data) {
            ++$count;
        }
        $this->assertEquals($response->getTotalCount(), $count, 'Compare count');
    }

    /**
     * @dataProvider dateLimitProvider
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $perPage
     * @return void
     */
    public function testLimit(string $startDate, string $endDate, int $perPage): void
    {
        $response = (new Transactions(API_KEY))->get($startDate, $endDate);
        $totalRows = 0;

        $pages = [];
        $totalRowsPerPage = 0;
        foreach($response->limit($perPage) as $data) {
            ++$totalRows;
            ++$totalRowsPerPage;
            if($totalRowsPerPage === $perPage) {
                $pages[] = $totalRowsPerPage;
                $totalRowsPerPage = 0;
            }
        }
        $pages[] = $totalRowsPerPage;  //record last page total

        $pageCount = count($pages);
        $this->assertEquals($pageCount, $response->getLastPage(), 'Total pages');

        foreach($pages as $i => $total) {
            $page = $i+1;
            if($page == $pageCount) {
                $this->assertEquals($total, $response->getCount(), "Total returned (for page $page)");
            } else {
                $this->assertEquals($total, $perPage, "Total returned (for page $page)");
            }
        }
        $this->assertEquals($response->getTotalCount(), $totalRows, 'Compare total rows');
    }

    /**
     * @dataProvider datePageLimitProvider
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $page
     * @param int $perPage
     * @return void
     */
    public function testPage(string $startDate, string $endDate, int $page, int $perPage): void
    {
        $response = (new Transactions(API_KEY))->get($startDate, $endDate);
        $totalRowsPerPage = 0;
        foreach($response->limit($perPage)->page($page) as $data) {
            ++$totalRowsPerPage;
        }
        $this->assertEquals($totalRowsPerPage, $response->getCount(), "Total returned");
    }
}
