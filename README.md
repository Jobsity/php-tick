# PHP Tick

A PHP client for Tickspot API. Currently supports v2 of the API.

[![Latest Stable Version](https://poser.pugx.org/jobsity/php-tick/v/stable)](https://packagist.org/packages/jobsity/php-tick)
[![Total Downloads](https://poser.pugx.org/jobsity/php-tick/downloads)](https://packagist.org/packages/jobsity/php-tick)
[![Latest Unstable Version](https://poser.pugx.org/jobsity/php-tick/v/unstable)](https://packagist.org/packages/jobsity/php-tick)
[![License](https://poser.pugx.org/jobsity/php-tick/license)](https://packagist.org/packages/jobsity/php-tick)

## Requirements
* PHP `>= 5.5.0`
* guzzlehttp/guzzle `^6.0`
* mefworks/log `^1.0`

### Development Requirements
* phpunit/phpunit `^4.6.0`

##Available endpoints
* Entry
* Task
* Project

## How to use

###Credentials
Get your access token and subscription ID from [Tickspot](https://www.tickspot.com/users)

###API calls

All calls to Tickspot's API are made over HTTPS protocol.

###Example
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

// Create entry with required parameters: hours, date, notes and task which entry belongs
$tick->entry->create(5, '2015-11-14', 'notes', '687756');

// Update entry atributes, with entry id as first parameter and hours as parameter for update
$tick->entry->update('56565', 3);

// Delete entry by its id
$tick->entry->delete($entryId);
```

