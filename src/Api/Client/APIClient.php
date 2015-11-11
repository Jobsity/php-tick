<?php

namespace Jobsity\PhpTick\Api\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class APIClient implements ClientInterface
{
    const BASE_URL = 'https://www.tickspot.com/';
    const ENDPOINT_URL = '/api/v2/';

    /* config access data */
    private $subscription_id;
    private $access_token;

    /* config data fo User Agent */
    private $company;
    private $email;

    /* The API base url */
    protected $api_url;

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

        /* generate the api url */
        $this->apiUrl = self::BASE_URL . $this->subscription_id . self::ENDPOINT_URL;

        $this->client = new Client();
    }

    public function get($endpoint, $query_params)
    {
        try {
            $request = $this->client->request('GET', $this->apiUrl . $endpoint .'.json',
                ['headers' =>
                    ['User-Agent' => $this->company."(".$this->email.")",
                    'Authorization' => "Token token=" . $this->access_token],
                'query' => $query_params
                ]);

            echo $request->getBody();
        }
        catch (ClientException $e) {
            echo $e->getResponse()->getStatusCode();
            echo $e->getResponse()->getReasonPhrase();
        }
    }

    public function post($endpoint, $data)
    {
        try {
            $request = $this->client->request('POST', $this->apiUrl . $endpoint . '.json',
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

    public function put($endpoint, $data)
    {
        try {
            $request = $this->client->request('PUT', $this->apiUrl . $endpoint . '.json',
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

    public function delete($endpoint)
    {
        try {
            $request = $this->client->request('DELETE', $this->apiUrl . $endpoint . '.json',
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