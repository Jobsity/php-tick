<?php
namespace Jobsity\PhpTick\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClienInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Jobsity\PhpTick\Http\Exception\ApiException;
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
     * @return \Jobsity\PhpTick\Http\ApiClient Created instance of the class.
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
     * @param \GuzzleHttp\ClientInterface   $client           Guzzler client.
     * @param \mef\Log\Logger               $logger           Logger instance.
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
    public function get($endpoint, array $queryParams = [])
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl . $endpoint . '.json', ['query' => $queryParams]);

            return json_decode((string)$response->getBody(), true);
        } catch (ClientException $e) {
            $this->logger->error('Error trying to request GET: {endpoint} {queryParams}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (ServerException $e) {
            $this->logger->error('Error trying to request GET: {endpoint} {queryParams}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $this->logger->error('Error trying to request GET: {endpoint} {queryParams}, server return: {code} : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getMessage()]);
                throw new ApiException($e->getResponse());
            } else {
                $this->logger->error('Error trying to request GET: {endpoint} {queryParams}, server return : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'message' => $e->getMessage()]);
                throw new Exception('Something went wrong');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function post($endpoint, array $queryParams = [], array $data)
    {
        try {
            $response = $this->client->request('POST', $this->apiUrl . $endpoint . '.json', [
                'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
                'query' => $queryParams,
                'json' => $data
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            $this->logger->error('Error trying to request POST: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'data'=> $data,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (ServerException $e) {
            $this->logger->error('Error trying to request POST: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'data'=> $data,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $this->logger->error('Error trying to request POST: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'data'=> $data,
                    'code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getResponse()->getReasonPhrase()]);
                throw new ApiException($e->getResponse());
            } else {
                $this->logger->error('Error trying to request POST: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'data'=> $data,
                    'message' => $e->getMessage()]);
                throw new Exception('Something went wrong');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($endpoint, array $queryParams = [], array $data)
    {
        try {
            $response = $this->client->request('PUT', $this->apiUrl . $endpoint . '.json', [
                'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
                'query' => $queryParams,
                'json' => $data
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (ClientException $e) {
            $this->logger->error('Error trying to request PUT: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'data'=> $data,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (ServerException $e) {
            $this->logger->error('Error trying to request PUT: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'data'=> $data,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $this->logger->error('Error trying to request PUT: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'data'=> $data,
                    'code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getResponse()->getReasonPhrase()]);
                throw new ApiException($e->getResponse());
            } else {
                $this->logger->error('Error trying to request PUT: {endpoint} {queryParams} with data {data}, server return: {code} : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'data'=> $data,
                    'message' => $e->getMessage()]);
                throw new Exception('Something went wrong');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($endpoint, array $queryParams = [])
    {
        try {
            $response = $this->client->request('DELETE', $this->apiUrl . $endpoint . '.json', ['query' => $queryParams]);

            return $response;
        } catch (ClientException $e) {
            $this->logger->error('Error trying to request DELETE: {endpoint} {queryParams}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (ServerException $e) {
            $this->logger->error('Error trying to request DELETE: {endpoint} {queryParams}, server return: {code} : {message}', [
                'endpoint' => $endpoint,
                'queryParams'=> $queryParams,
                'code' => $e->getResponse()->getStatusCode(),
                'message' => $e->getResponse()->getReasonPhrase()]);
            throw new ApiException($e->getResponse());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $this->logger->error('Error trying to request DELETE: {endpoint} {queryParams}, server return: {code} : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getResponse()->getReasonPhrase()]);
                throw new ApiException($e->getResponse());
            } else {
                $this->logger->error('Error trying to request DELETE: {endpoint} {queryParams}, server return: {code} : {message}', [
                    'endpoint' => $endpoint,
                    'queryParams'=> $queryParams,
                    'message' => $e->getMessage()]);
                throw new Exception('Something went wrong');
            }
        }
    }
}
