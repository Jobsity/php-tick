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
     * Get Request
     *
     * @param string   $endpoint      Final endpoint
     * @param array    $queryParams   Parameters for quering
     *
     * @return mixed
     */
    public function get($endpoint, array $queryParams);

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
