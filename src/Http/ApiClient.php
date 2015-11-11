<?php
namespace Jobsity\PhpTick\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use mef\Log\StandardLogger;

/**
 * Class ApiClient
 *
 * @package Jobsity\PhpTick
 */
class ApiClient implements ClientInterface
{
    const BASE_URL = 'https://www.tickspot.com/';
    const ENDPOINT_URL = '/api/v2/';

    /**
     * @var string User’s subscription id
     */
    private $subscriptionId;

    /**
     * @var string User's access token
     */
    private $accessToken;

    /**
     * @var string User's company
     */
    private $company;

    /**
     * @var string User's email
     */
    private $email;

    /**
    * @var string API url
    */
    private $apiUrl;

    /**
     * @var Client Guzzle Client Handler
     */
    private $client;

    /**
     * Constructor
     *
     * @param string   $subscriptionId   Subscription id of the user.
     * @param string   $accessToken      Access token of the user.
     * @param string   $company          User's company.
     * @param string   $email            User's email.
     */
    public function __construct($subscriptionId, $accessToken, $company, $email)
    {
        $this->subscriptionId = (string)$subscriptionId;
        $this->accessToken = (string)$accessToken;
        $this->company = (string)$company;
        $this->email = (string)$email;

        $this->apiUrl = self::BASE_URL . $this->subscriptionId . self::ENDPOINT_URL;

        $this->client = new Client(['headers' =>
            ['User-Agent' => $this->company . "(" . $this->email . ")",
                'Authorization' => "Token token=" . $this->accessToken]
        ]);

        $this->logger = new StandardLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function get($endpoint, array $queryParams)
    {
        try {
            $request = $this->client->request('GET', $this->apiUrl . $endpoint . '.json',
                array('query' => $queryParams));

            return json_decode((string)$request->getBody());
        } catch (ClientException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (ServerException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getMessage()]);
            } else
                return $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            return $this->logger->error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function post($endpoint, array $data)
    {
        try {
            $request = $this->client->request('POST', $this->apiUrl . $endpoint . '.json',
                array('headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8'
                ),
                    'json' => $data
                ));

            return (string)$request->getBody();
        } catch (ClientException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (ServerException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getMessage()]);
            } else
                return $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            return $this->logger->error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($endpoint, array $data)
    {
        try {
            $request = $this->client->request('PUT', $this->apiUrl . $endpoint . '.json',
                array('headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8'
                ),
                    'json' => $data
                ));

            return $request->getStatusCode();
        } catch (ClientException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (ServerException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getMessage()]);
            } else
                return $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            return $this->logger->error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($endpoint)
    {
        try {
            $request = $this->client->request('DELETE', $this->apiUrl . $endpoint . '.json', array());

            return $request->getStatusCode();
        } catch (ClientException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (ServerException $e) {
            return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $this->logger->error('{code} : {message}', ['code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getMessage()]);
            } else
                return $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            return $this->logger->error($e->getMessage());
        }
    }
}
