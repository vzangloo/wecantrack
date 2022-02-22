<?php

namespace WeCanTrack\Response;

use WeCanTrack\API\Transactions;
use WeCanTrack\Helper\Curl;

/**
 * Class TransactionResponse
 * @package WeCanTrack\Response
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class TransactionResponse extends Response implements \Iterator
{
    protected Transactions $transaction;
    protected array $body = [];
    protected int $retry = 1;
    protected int $delay = 1; // seconds
    private bool $singlePage = false;
    private bool $isLastItem = false;
    private int $index = 0;
    private int $totalCount = 0;

    private $key;
    private $value;

    /**
     * @param Transactions $transaction
     * @param int $maxRetry Maximum retry if request failed.
     * @param int $delay
     */
    public function __construct(Transactions $transaction, int $maxRetry = 10, int $delay = 1)
    {
        $this->transaction = $transaction;
        $this->retry = $maxRetry;
        $this->delay = $delay;
    }

    /**
     * Set the current page.
     *
     * @param int $page The current page.
     * @return $this
     */
    public function page(int $page): self
    {
        $this->transaction->page($page);
        $this->singlePage = true;
        return $this;
    }

    /**
     * Limit the maximum transactions per request.
     *
     * @param int $limit The maximum transactions per request.
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->transaction->limit($limit);
        return $this;
    }

    /**
     * Get total count for entire dataset
     *
     * @return int The total count.
     */
    public function getTotalCount(): int
    {
        if(empty($this->body)) {
            $this->requestData();
        }
        return (int)($this->body['total'] ?? 0);
    }

    /**
     * Get the returned total for the current page
     *
     * @return int The total returned for the current page.
     */
    public function getCount(): int
    {
        return  $this->getCurrentPage() === $this->getLastPage()
                    ? count($this->body['data'])
                    : $this->getPerPage();
    }

    /**
     * Get the current page number.
     *
     * @return int The current page number.
     */
    public function getCurrentPage(): int
    {
        return (int) ($this->body['current_page'] ?? 1);
    }

    /**
     * Get the last page number.
     *
     * @return int The last page number.
     */
    public function getLastPage(): int
    {
        return (int) ($this->body['last_page'] ?? 1);
    }

    /**
     * Get the total number of pages for the current dataset.
     *
     * @return int The total number of pages.
     */
    public function getTotalPages(): int
    {
        return $this->getLastPage();
    }

    /**
     * Get all available links values for the current dataset.
     *
     * @return array All the link values.
     */
    public function getLinks(): array
    {
        return $this->body['links'] ?? [];
    }

    /**
     * Get the first page URL.
     *
     * @return string|null The first page URL.
     */
    public function getStartUrl(): ?string
    {
        return $this->body['first_page_url'] ?? null;
    }

    /**
     * Get the last page URL.
     *
     * @return string|null The last page URL.
     */
    public function getEndUrl(): ?string
    {
        return $this->body['last_page_url'] ?? null;
    }

    /**
     * Get the previous page URL.
     *
     * @return string|null The previous page URL.
     */
    public function getPreviousUrl(): ?string
    {
        return $this->body['prev_page_url'] ?? null;
    }

    /**
     * Get the next page URL.
     *
     * @return string|null The next page URL.
     */
    public function getNextUrl(): ?string
    {
        return $this->body['next_page_url'] ?? null;
    }

    /**
     * Get the maximum returned data for current request.
     *
     * @return int The maximum returned data for current request.
     */
    public function getPerPage(): int
    {
        return $this->singlePage
            ? $this->totalCount
            : (int) ($this->body['per_page'] ?? 0);
    }

    /**
     * Get the current index value.
     *
     * @return int The index value.
     */
    public function index(): int
    {
        return $this->index;
    }

    /**
     * Request data from endpoint.
     *
     * Will retry if request failed.
     *
     * @return void
     */
    protected function requestData(): void
    {
        if($url = $this->getNextUrl()) {
            $this->transaction->page($this->getCurrentPage() + 1);
        } else {
            $url = $this->transaction->getUrl();
        }

        $retry = 1;
        do{
            sleep($this->delay);
            $response = Curl::request($this->transaction->getHeaders())
                ->query($this->transaction->getPayloads())
                ->get($url);
        }while($response == false && ($retry++ <= $this->retry));

        if(!$response) {
            $this->addError(Curl::getError());
        } else {
            $this->body = $response;
            $this->value = current($this->body['data']);
            $this->key = key($this->body['data']);
        }
    }

    /**
     * Reset the current data.
     *
     * @return void
     */
    public function rewind(): void
    {
        if(empty($this->body)) {
            $this->requestData();
        }
        $this->index = 1;
        $this->totalCount = $this->singlePage? $this->getCount(): $this->getTotalCount();
    }

    /**
     * Check if current item is valid.
     *
     * @return bool True if current item is valid.
     */
    public function valid(): bool
    {
        if($this->hasError()) {
            return false;
        }
        $this->isLastItem = $this->index === $this->totalCount;
        return $this->index <= $this->totalCount;
    }

    /**
     * Get the current item value.
     *
     * @return mixed The current item value.
     */
    public function current()
    {
        return $this->value;
    }

    /**
     * Get current item key.
     *
     * @return mixed|null The current item key.
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Move to next item.
     *
     * @return void
     */
    public function next(): void
    {
        if(!$this->isLastItem && next($this->body['data']) === false) {
            $this->requestData();
        }

        $this->value = current($this->body['data']);
        $this->key = key($this->body['data']);
        ++$this->index;
    }
}
