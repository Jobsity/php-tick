# PHP Tick

A PHP client for Tickspot API. Currently supports v2 of the API.

## Requirements
* PHP `>= 5.5.0`
* guzzlehttp/guzzle `^6.0`
* mefworks/log `^1.0`

### Development Requirements
* phpunit/phpunit `^4.6.0`

## How to use
```php
// Make sure you require autoload file somewhere
require_once "../vendor/autoload.php";

use Jobsity\PhpTick\Tick;

// Get instance of Tick client
$tick = Tick::getInstance($subscriptionId, $accessToken, 'CompanyName', 'company@email.com');

// Start using the api

// Get entries created after 2015-11-14
$tick->entry->getList('2015-11-14');

// Get entry by its id
$tick->entry->get($entryId);
```
