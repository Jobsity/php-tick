<?php
/**
 * Created by PhpStorm.
 * User: jobsity
 * Date: 11/11/15
 * Time: 11:18 AM
 */

namespace Jobsity\PhpTick\Api\Client;


interface ClientInterface
{
    public function get($endpoint, $query_params);

    public function post($endpoint, $data);

    public function put($endpoint, $data);

    public function delete($endpoint);
}