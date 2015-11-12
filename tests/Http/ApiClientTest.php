<?php
namespace Jobsity\PhpTick\Test;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Jobsity\PhpTick\Http\ApiClient;
use mef\Log\StandardLogger;
use PHPUnit_Framework_TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

/**
 * Class ApiClientTest
 *
 * @package Jobsity\PhpTick\Test
 */
class ApiClientTest extends PHPUnit_Framework_TestCase{

    /**
     * @var string User's subscription id
     */
    protected $subscriptionId;

    /**
     * @var string User's access token
     */
    protected $accessToken;

    /**
     * @var string User's company
     */
    protected $company;

    /**
     * @var string User's email
     */
    protected $email;

    /**
     * ApiClientTest Setup
     */
    protected function setup()
    {
        $this->subscriptionId = '456789';
        $this->accessToken = '2387654321234578';
        $this->company = 'Company';
        $this->email = 'some@company.com';
    }

    /**
     * Test Get Request
     *
     * @covers ApiClient::get
     * @uses ApiClient::__construct
     *
     * @param string    $endpoint      Final endpoint
     * @param array     $queryParams   Parameters for querying
     */
    public function getRequestSuccessResponse($endpoint, array $queryParams)
    {
        $responseParams = json_encode([['id'=>'8989985'], ['id' => '2323232']]);
        $mock = new MockHandler([
            new Response(200, [], $responseParams),
            new Response(401),
            new Response(500)
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $logger = new StandardLogger();

        $apiClient = new ApiClient($client, $logger, $this->subscriptionId, $this->accessToken, $this->company,
            $this->email);

        $response = $apiClient->get($endpoint, $queryParams);
        $this->assertTrue(is_array($response));
        $this->assertJsonStringEqualsJsonString($responseParams, json_encode($response));

        $response = $apiClient->get($endpoint, $queryParams);
        $this->assertEquals(401, $response);

        $response = $apiClient->get($endpoint, $queryParams);
        $this->assertEquals(500, $response);
    }

    /**
     * Test Post Request
     *
     * @param string    $endpoint   Final endpoint
     * @param array     $data       Data to insert
     *
     * @covers ApiClient::post
     * @uses ApiClient::__construct
     */
    public function postRequestSuccessResponse($endpoint, array $data)
    {
        $responseParams = json_encode(['id' => '48917908', 'date' => "2015-11-11", 'hours' => '6',
            'task_id' => '1235', 'notes' => 'some']);
        $mock = new MockHandler([
            new Response(200, [], $responseParams),
            new Response(401),
            new Response(500)
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $logger = new StandardLogger();

        $apiClient = new ApiClient($client, $logger, $this->subscriptionId, $this->accessToken, $this->company,
            $this->email);

        $response = $apiClient->post($endpoint, $data);
        $this->assertJsonStringEqualsJsonString($responseParams, json_encode($response));

        $response = $apiClient->post($endpoint, $data);
        $this->assertEquals(401, $response);

        $response = $apiClient->post($endpoint, $data);
        $this->assertEquals(500, $response);
    }

    /**
     * Test Put Request
     *
     * @param string    $endpoint   Final endpoint
     * @param array     $data       Data to update
     *
     * @covers ApiClient::put
     * @uses ApiClient::__construct
     */
    public function putRequestSuccessResponse($endpoint, array $data)
    {
        $responseParams = json_encode(['hours' => '2', 'notes' => 'some test v2']);
        $mock = new MockHandler([
            new Response(200, [], $responseParams),
            new Response(401),
            new Response(500)
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $logger = new StandardLogger();

        $apiClient = new ApiClient($client, $logger, $this->subscriptionId, $this->accessToken, $this->company,
            $this->email);

        $response = $apiClient->put($endpoint, $data);
        $this->assertEquals(200, $response);

        $response = $apiClient->put($endpoint, $data);
        $this->assertEquals(401, $response);

        $response = $apiClient->put($endpoint, $data);
        $this->assertEquals(500, $response);
    }

    /**
     * Test Delete Request
     *
     * @param string    $endpoint   Final endpoint
     *
     * @covers ApiClient::delete
     * @uses ApiClient::__construct
     */
    public function deleteRequestSuccessResponse($endpoint)
    {
        $responseParams = json_encode(['hours' => '2', 'notes' => 'some test v2']);
        $mock = new MockHandler([
            new Response(204, [], $responseParams),
            new Response(401),
            new Response(500)
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $logger = new StandardLogger();

        $apiClient = new ApiClient($client, $logger, $this->subscriptionId, $this->accessToken, $this->company,
            $this->email);

        $response = $apiClient->delete($endpoint);
        $this->assertEquals(204, $response);

        $response = $apiClient->delete($endpoint);
        $this->assertEquals(401, $response);

        $response = $apiClient->delete($endpoint);
        $this->assertEquals(500, $response);
    }

    /**
     * Funcion to call all the tests with parameters
     */
    public function testGeneral(){
        $this->getRequestSuccessResponse('entries', ['updated_at' => '2015-11-11']);

        $postData = [
            'date' => '2015-11-11',
            'hours' => '5',
            'task_id' => '1235',
            'notes' => 'some'
        ];
        $this->postRequestSuccessResponse('entries', $postData);

        $putData = array(
            'hours' => '2',
            'notes' => 'some test v2'
        );
        $this->putRequestSuccessResponse('entries/5678976', $putData);

        $this->deleteRequestSuccessResponse('entries/5678976');
    }

}
