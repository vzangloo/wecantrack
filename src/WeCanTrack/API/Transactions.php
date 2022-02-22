<?php

namespace WeCanTrack\API;

use Carbon\Carbon;
use WeCanTrack\Response\TransactionResponse;

/**
 * Class Transactions
 * @package WeCanTrack\API
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2022 Saleduck Asia Sdn Bhd
 */
class Transactions extends Request
{
    /**
     * @var string Get transactions endpoint
     */
    protected string $api = 'https://api.wecantrack.com/api/v3/transactions';

    /**
     * @var int maximum number of transactions to return
     */
    protected int $maxPerRequest = 1000;

    const
        ORDER_DATE = 'order_date',
        MODIFIED_DATE = 'modified_date',
        CLICK_DATE = 'click_date',
        VALIDATION_DATE = 'validation_date',
        LAST_WCT_UPDATE = 'last_wct_update',

        STATUS_PENDING = 'pending',
        STATUS_APPROVED = 'approved',
        STATUS_DECLINED = 'declined';

    /**
     * Filter transactions by network ID.
     *
     * @param string $id Network ID.
     * @return $this
     */
    public function networkId(string $id): self
    {
        $this->payloads['network_id'] = $id;
        return $this;
    }

    /**
     * Filter transactions by account ID.
     *
     * @param int $id Account ID.
     * @return $this
     */
    public function networkAccountId(int $id): self
    {
        $this->payloads['network_account_id'] = $id;
        return $this;
    }

    /**
     * Filter transactions by account tags.
     *
     * @param array $tags Array of tags.
     * @return $this
     */
    public function networkAccountTags(array $tags): self
    {
        $this->payloads['network_account_tags'] = $tags;
        return $this;
    }

    /**
     * Filter transactions by status.
     *
     * Available status values: Transactions::STATUS_PENDING, Transactions::STATUS_APPROVED, Transactions::STATUS_DECLINED
     *
     * @param array $status Array of status.
     * @return $this
     */
    public function status(array $status = []): self
    {
        if (count(array_uintersect(['pending', 'approved', 'declined'], $status, 'strcmp')) === count($status)) {
            $this->payloads['status'] = $status;
        } else {
            $this->addError('Invalid status');
        }
        return $this;
    }

    /**
     * Get transactions from a specific page.
     *
     * @param int $page The page number.
     * @return $this
     */
    public function page(int $page): self
    {
        $this->payloads['page'] = ($page <= 0) ? 1 : $page;
        return $this;
    }

    /**
     * Set the number of transactions to return per page.
     *
     * If the limit more than the maxPerRequest, the maxPerRequest will be used instead.
     *
     * @param int $limit The number of transactions to return per page.
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->payloads['limit'] = ($limit > $this->maxPerRequest) ? $this->maxPerRequest : $limit;
        return $this;
    }

    /**
     * Get transactions from a specific date range.
     *
     * @param string $fromDate  The start date of the date range.
     * @param string $toDate    The end_date should be at least 1 day greater than the start_date, else end_date will be (start_date + 1 day)
     * @param string $type      The date_type for this date range
     * @return TransactionResponse
     */
    public function get(string $fromDate, string $toDate, string $type = self::LAST_WCT_UPDATE): TransactionResponse
    {
        $this->payloads['date_type'] = $type;

        $fDate = Carbon::createFromFormat('Y-m-d', $fromDate);
        $tDate = Carbon::createFromFormat('Y-m-d', $toDate);
        if($fDate >= $tDate) {
            $tDate = Carbon::createFromFormat('Y-m-d', $fromDate)->addDay();
        }
        $this->payloads['start_date'] = $fDate->format('Y-m-d\TH:i:s');
        $this->payloads['end_date'] = $tDate->format('Y-m-d\TH:i:s');
        return new TransactionResponse($this);
    }
}
