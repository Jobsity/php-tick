<?php
namespace Jobsity\PhpTick\Http;

/**
 * Interface ClientInterface
 *
 * @package Jobsity\PhpTick\Api\Client
 */
interface ClientInterface
{
    /**
     * @param $endpoint
     * @param $queryParams
     *
     * @return mixed
     */
    public function get($endpoint, $queryParams);

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
