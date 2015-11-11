<?php
namespace Jobsity\PhpTick\Api\Client;

/**
 * Interface ClientInterface
 *
 * @package Jobsity\PhpTick\Api\Client
 */
interface ClientInterface
{
    /**
     * @param $endpoint
     * @param $query_params
     *
     * @return mixed
     */
    public function get($endpoint, $query_params);

    /**
     * @param $endpoint
     * @param $data
     *
     * @return mixed
     */
    public function post($endpoint, $data);

    /**
     * @param $endpoint
     * @param $data
     *
     * @return mixed
     */
    public function put($endpoint, $data);

    /**
     * @param $endpoint
     *
     * @return mixed
     */
    public function delete($endpoint);
}