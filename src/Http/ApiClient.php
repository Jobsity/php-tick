<?php
namespace Jobsity\PhpTick\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClienInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use mef\Log\Logger;
use mef\Log\StandardLogger;

/**
 * Class ApiClient
 *
 * @package Jobsity\PhpTick\Http
 */
class ApiClient implements ClientInterface
{
    const BASE_URL = 'https://www.tickspot.com/';
    const ENDPOINT_URL = '/api/v2/';

    /**
     * @var string User's subscription id
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
    * @var string API URL
    */
    private $apiUrl;

    /**
     * @var Client Guzzle Client Handler
     */
    private $client;

    /**
     * Return an instance of the class.
     *
     * @param string   $subscriptionId   Subscription id of the user.
     * @param string   $accessToken      Access token of the user.
     * @param string   $company          User's company.
     * @param string   $email            User's email.
     *
     * @return Jobsity\PhpTick\Http\ApiClient Created instance of the class.
     */
    public static function getInstance($subscriptionId, $accessToken, $company, $email)
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => $company . '(' . $email . ')',
                'Authorization' => 'Token token=' . $accessToken
            ]
        ]);

        $logger =  new StandardLogger();

        return new self($client, $logger, $subscriptionId, $accessToken, $company, $email);
    }

    /**
     * Constructs ApiClient
     *
     * @param GuzzleHttp\ClientInterface   $client           Guzzler client.
     * @param mef\Log\Logger               $logger           Logger instance.
     * @param string                       $subscriptionId   Subscription id of the user.
     * @param string                       $accessToken      Access token of the user.
     * @param string                       $company          User's company.
     * @param string                       $email            User's email.
     */
    public function __construct(GuzzleClienInterface $client, Logger $logger, $subscriptionId, $accessToken, $company, $email)
    {
        $this->subscriptionId = (string)$subscriptionId;
        $this->accessToken = (string)$accessToken;
        $this->company = (string)$company;
        $this->email = (string)$email;

        $this->apiUrl = self::BASE_URL . $this->subscriptionId . self::ENDPOINT_URL;

        $this->client = $client;

        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function get($endpoint, array $queryParams)
    {
        try {
            $request = $this->client->request('GET', $this->apiUrl . $endpoint . '.json', ['query' => $queryParams]);

            return json_decode((string)$request->getBody(), true);
        } catch (ClientException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (ServerException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getStatusCode();
            } else {
                return $e->getCode();
            }
        } catch (Exception $e) {
            return $e->getCode();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function post($endpoint, array $data)
    {
        try {
            $request = $this->client->request('POST', $this->apiUrl . $endpoint . '.json', [
                'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
                'json' => $data
            ]);

            return json_decode((string) $request->getBody());
        } catch (ClientException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (ServerException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getStatusCode();
            } else {
                return $e->getCode();
            }
        } catch (Exception $e) {
            return $e->getCode();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($endpoint, array $data)
    {
        try {
            $request = $this->client->request('PUT', $this->apiUrl . $endpoint . '.json', [
                'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
                'json' => $data
            ]);

            return $request->getStatusCode();
        } catch (ClientException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (ServerException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getStatusCode();
            } else {
                return $e->getCode();
            }
        } catch (Exception $e) {
            return $e->getCode();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($endpoint)
    {
        try {
            $request = $this->client->request('DELETE', $this->apiUrl . $endpoint . '.json', []);

            return $request->getStatusCode();
        } catch (ClientException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (ServerException $e) {
            return $e->getResponse()->getStatusCode();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getStatusCode();
            } else {
                return $e->getCode();
            }
        } catch (Exception $e) {
            return $e->getCode();
        }
    }
}
