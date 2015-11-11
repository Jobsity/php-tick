<?php

require_once "../vendor/autoload.php";

use Jobsity\PhpTick\Api\TickspotAPI;

$_api = new TickspotAPI(
    '40556', '10b597860b7032a6c0293ebdf5e0efc4', 'Jobsity','yudeikis.orta@jobsity.com'
);


//$_api->createEntry();
//$_api->deleteEntry();
$_api->updateEntry();
$_api->getEntries(null, '2015-11-11', '2015-11-11');
