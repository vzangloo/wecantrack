<?php

namespace WeCanTrack\Response;

use WeCanTrack\API\Transactions;
use WeCanTrack\Helper\Curl;

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

    public function page(int $page): self
    {
        $this->transaction->page($page);
        $this->singlePage = true;
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->transaction->limit($limit);
        return $this;
    }

    /**
     * Get total count for entire dataset
     *
     * @return int
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
     * @return int
     */
    public function getCount(): int
    {
        return  $this->getCurrentPage() === $this->getLastPage()
                    ? count($this->body['data'])
                    : $this->getPerPage();
    }

    public function getCurrentPage(): int
    {
        return (int) ($this->body['current_page'] ?? 1);
    }

    public function getLastPage(): int
    {
        return (int) ($this->body['last_page'] ?? 1);
    }

    public function getTotalPages(): int
    {
        return $this->getLastPage();
    }

    public function getLinks(): array
    {
        return $this->body['links'] ?? [];
    }

    public function getStartUrl(): ?string
    {
        return $this->body['first_page_url'] ?? null;
    }

    public function getEndUrl(): ?string
    {
        return $this->body['last_page_url'] ?? null;
    }

    public function getPreviousUrl(): ?string
    {
        return $this->body['prev_page_url'] ?? null;
    }

    public function getNextUrl(): ?string
    {
        return $this->body['next_page_url'] ?? null;
    }

    public function getPerPage(): int
    {
        return $this->singlePage
            ? $this->totalCount
            : (int) ($this->body['per_page'] ?? 0);
    }

    public function index(): int
    {
        return $this->index;
    }

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

        if($response == false) {
            $this->addError(Curl::getError());
        } else {
            $this->body = $response;
            $this->value = current($this->body['data']);
            $this->key = key($this->body['data']);
        }
    }

    public function rewind(): void
    {
        if(empty($this->body)) {
            $this->requestData();
        }
        $this->index = 1;
        $this->totalCount = $this->singlePage? $this->getCount(): $this->getTotalCount();
    }

    public function valid(): bool
    {
        if($this->hasError()) {
            return false;
        }
        $this->isLastItem = $this->index === $this->totalCount;
        return $this->index <= $this->totalCount;
    }

    public function current()
    {
        return $this->value;
    }

    public function key()
    {
        return $this->key;
    }

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
