# WeCanTrack
WeCanTrack offers a software solution that helps (affiliate) publishers in their everday business activities, by providing them with insights on which content and campaigns are converting and how much revenue they are generating.

### Requirements
- PHP >= 7.4

### Installation
1. Install package using composer
```shell
$ composer require vzangloo/wecantrack
```

### Basic Usage

#### ClickOut URL
```php
use WeCanTrack\API\Clickout;

$clickout = (new Clickout(API_KEY))
                    ->affiliateUrl('https://www.awin1.com/cread.php?awinmid=10921&awinaffid=211395&clickref2=MY&...')
                    ->clickoutUrl('https://your-clickout-url.com/clickout')
                    ->ipAddress('your-ip-address')
                    ->metadata([
                        'custom_1' => 'custom_data',
                        'custom_2' => 'custom_data2',
                    ])
                    ->get();

if($clickout->isValid()) {
    echo $clickout->getAffiliateUrl();
    echo $clickout->getReference(); // return WCT reference. Example: wct200514135314e7x4d
} else {
    var_dump($clickout->getErrors());
}
```

### Documentation
See [Full Documentation](https://docs.wecantrack.com/)


#### All Network Accounts
```php
use WeCanTrack\API\Networks, NetworkAccounts;

$accounts = (new NetworkAccounts(API_KEY))->get();

foreach($accounts as $account) {
    echo $account['id'];
    echo $account['name'];
    echo $account['network_id'];
    echo $account['is_enabled'];
    ......
}

echo $accounts->getCount(); // return Accounts count
```

#### All Network Accounts with ID filter
```php
use WeCanTrack\API\Networks, NetworkAccounts;

$accounts = (new NetworkAccounts(API_KEY))->ids(12)->get(); // get account where its id = 12
$accounts = (new NetworkAccounts(API_KEY))->ids([12, 123, 1234])->get(); // get accounts where its ids are 12, 123, 1234

foreach($accounts as $account) {
    echo $account['id'];
    echo $account['name'];
    echo $account['network_id'];
    echo $account['is_enabled'];
    ......
}

echo $accounts->getCount(); // return Accounts count
```

#### All Transactions
```php
use WeCanTrack\API\Transactions;

$records = (new Transactions(API_KEY))->get($startDate, $endDate);

foreach($records as $row) {
    echo $row['transaction_id'];
    echo $row['reference']; // return wct200514135314e7x4d
    echo $row['order_date'];
    echo $row['validation_date'];
    echo $row['status'];
    echo $row['sale_amount'];
    echo $row['commission_amount'];
    var_dump($row['click_metadata']);
    ......
}

echo $records->getTotalCount(); // get all record count.
```

#### All Transactions With Filter
```php
use WeCanTrack\API\Transactions;

$records = (new Transactions(API_KEY))
            ->networkAccountId(123)
            ->status([
                Transactions::STATUS_PENDING,
                Transactions::STATUS_APPROVED,
            ])
            ->get($startDate, $endDate, Transactions::LAST_WCT_UPDATE);

foreach($records as $row) {
    echo $row['transaction_id'];
    echo $row['reference']; // return wct200514135314e7x4d
    ......
}

echo $records->getTotalCount(); // get all record count.
```

#### Get All Transactions With Limit
```php
use WeCanTrack\API\Transactions;

$records = (new Transactions(API_KEY))
            ->status([
                Transactions::STATUS_PENDING,
            ])
            ->get($startDate, $endDate);

// every page request return max 500 rows only
foreach($records->limit(500) as $row) {
    echo $row['transaction_id'];
    echo $row['reference']; // return wct200514135314e7x4d
    ......
}

echo $records->getTotalCount(); // get all record count.
```

#### Get All Transactions For a Page
```php
use WeCanTrack\API\Transactions;

$records = (new Transactions(API_KEY))
            ->status([
                Transactions::STATUS_PENDING,
            ])
            ->get($startDate, $endDate);

// return max 600 rows from page 3 only.
foreach($records->limit(600)->page(3) as $row) {
    echo $row['transaction_id'];
    echo $row['reference']; // return wct200514135314e7x4d
    ......
}

echo $records->getTotalCount(); // get all record count.
echo $records->getCount(); // get the rows count for current page.
```

## License

