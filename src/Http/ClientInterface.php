<?php
namespace Jobsity\PhpTick\Http;

/**
 * Interface ClientInterface
 *
 * @package Jobsity\PhpTick\Http
 */
interface ClientInterface
{
    /**
     * Get Request
     *
     * @param string    $endpoint      Final endpoint
     * @param array     $queryParams   Parameters for querying
     *
     * @return mixed
     */
    public function get($endpoint, array $queryParams);

    /**
     * Get request for paginated results
     *
     * @param string    $endpoint      Final endpoint
     * @param array     $queryParams   Parameters for querying
     *
     * @return mixed
     */
    public function getAll($endpoint, array $queryParams);

    /**
     * Post Request
     *
     * @param string    $endpoint   Final endpoint
     * @param array     $data       Data to insert
     *
     * @return mixed
     */
    public function post($endpoint, array $queryParams, array $data);

    /**
     * Put Request
     *
     * @param string    $endpoint   Final endpoint
     * @param array     $data       Data to update
     *
     * @return mixed
     */
    public function put($endpoint, array $queryParams, array $data);

    /**
     * Delete Request
     *
     * @param string    $endpoint   Final endpoint
     *
     * @return mixed
     */
    public function delete($endpoint, array $queryParams);
}
