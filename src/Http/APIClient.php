<?php
namespace Jobsity\PhpTick\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class APIClient
 *
 * @package Jobsity\PhpTick\Api\Client
 */
class APIClient implements ClientInterface
{
    const BASE_URL = 'https://www.tickspot.com/';
    const ENDPOINT_URL = '/api/v2/';

    /**
     * @var string User’s subscription id
     */
    private $subscription_id;

    /**
     * @var string User’s access token
     */
    private $access_token;

    /**
     * @var string User’s company
     */
    private $company;

    /**
     * @var string User’s email
     */
    private $email;

    /**
    * @var string API url
    */
    private $api_url;

    /**
     * @var Client Guzzle Client Handler
     */
    private $client;

    /**
     * Constructor
     *
     * @param string   $subscription_id   Subscription id of the user.
     * @param string   $access_token      Access token of the user.
     * @param string   $company           User's company.
     * @param string   $email             User's email.
     */
    public function __construct($subscription_id, $access_token, $company, $email)
    {
        $this->subscription_id = (string)$subscription_id;
        $this->access_token = (string)$access_token;
        $this->company = (string)$company;
        $this->email = (string)$email;

        $this->api_url = self::BASE_URL . $this->subscription_id . self::ENDPOINT_URL;

        $this->client = new Client();
    }

    /**
     * Get Request
     *
     * @param string $endpoint      Final endpoint
     * @param array $queryParams   Parameters for quering
     *
     * @return mixed
     */
    public function get($endpoint, $queryParams)
    {
        try {
            $request = $this->client->request('GET', $this->api_url . $endpoint .'.json',
                ['headers' =>
                    ['User-Agent' => $this->company."(".$this->email.")",
                    'Authorization' => "Token token=" . $this->access_token],
                'query' => $queryParams
                ]);

            echo $request->getBody();
        }
        catch (ClientException $e) {
            echo $e->getResponse()->getStatusCode();
            echo $e->getResponse()->getReasonPhrase();
        }
    }

    /**
     * Post Request
     *
     * @param string $endpoint  Final endpoint
     * @param array $data       Data to insert
     *
     * @return mixed
     */
    public function post($endpoint, $data)
    {
        try {
            $request = $this->client->request('POST', $this->api_url . $endpoint . '.json',
                array('headers' => array(
                    'User-Agent' => $this->company . "(" . $this->email . ")",
                    'Authorization' => "Token token=" . $this->access_token,
                    'Content-Type' => 'application/json; charset=utf-8'
                ),
                    'json' => $data
                ));

            echo $request->getStatusCode();
        }
        catch (ClientException $e) {
            echo $e->getResponse()->getStatusCode();
            echo $e->getResponse()->getReasonPhrase();
        }
    }

    /**
     * Put Request
     *
     * @param string $endpoint  Final endpoint
     * @param array $data       Data to update
     *
     * @return mixed
     */
    public function put($endpoint, $data)
    {
        try {
            $request = $this->client->request('PUT', $this->api_url . $endpoint . '.json',
                array('headers' => array(
                    'User-Agent' => $this->company . "(" . $this->email . ")",
                    'Authorization' => "Token token=" . $this->access_token,
                    'Content-Type' => 'application/json; charset=utf-8'
                ),
                    'json' => $data
                ));

            echo $request->getStatusCode();
        }
        catch (ClientException $e) {
            echo $e->getResponse()->getStatusCode();
            echo $e->getResponse()->getReasonPhrase();
        }
    }

    /**
     * Delete Request
     *
     * @param string $endpoint  Final endpoint
     *
     * @return mixed
     */
    public function delete($endpoint)
    {
        try {
            $request = $this->client->request('DELETE', $this->api_url . $endpoint . '.json',
                array('headers' => array(
                        'User-Agent' => $this->company . "(" . $this->email . ")",
                        'Authorization' => "Token token=" . $this->access_token
                    )
                ));

            echo $request->getStatusCode();
        }
        catch (ClientException $e) {
            echo $e->getResponse()->getStatusCode();
            echo $e->getResponse()->getReasonPhrase();
        }
    }
}
