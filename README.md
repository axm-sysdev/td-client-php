# Treasure Data API library for PHP

Treasure Data API library for PHP

# Requirements

- PHP 7.0+

# Installation

```
composer require axm-sysdev/td-client-php
```

# Usage

```php
<?php

require_once 'vendor/autoload.php';

use AXM\TD\Client;
use AXM\TD\Job;

$client = new Client('YOUR-API-KEY-HERE');

$jobId = $client->query('mydatabase', 'hive', 'SELECT * FROM mytable WHERE value >= 500');

while (true) {
    $status = $client->jobStatus($jobId);
    if (Job::isFinished($status)) {
        break;
    }
    sleep(1);
}

$result = $client->jobResult($jobId);
```

### Query options

```php
$client->hiveQuery($dbName, $query, ['priority' => Job::PRIORITY_VERY_HIGH, 'engine_version' => 'stable']);
```

### Client Options

Use the guzzle option.  
[Document](https://docs.guzzlephp.org/en/6.5/request-options.html).


```php
$options['client_config'] = ['timeout' => 1];

$client = new Client('YOUR-API-KEY-HERE', $options);
```
