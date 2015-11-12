<?php
namespace Jobsity\PhpTick\Test\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jobsity\PhpTick\Http\ApiClient;
use Jobsity\PhpTick\Http\Exception\ApiException;
use mef\Log\StandardLogger;
use PHPUnit_Framework_TestCase;

/**
 * Class ApiClientTest
 *
 * @package Jobsity\PhpTick\Test\Http
 *
 * @coversDefaultClass Jobsity\PhpTick\Http\ApiClient
 */
class ApiClientTest extends PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        $this->subscriptionId = '456789';
        $this->accessToken = '2387654321234578';
        $this->company = 'Company';
        $this->email = 'some@company.com';
        $this->logger = new StandardLogger();
    }

    /**
     * @covers ::getInstance
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     */
    public function testGetInstance()
    {
        $apiClient = ApiClient::getInstance(
            $this->subscriptionId,
            $this->accessToken,
            $this->company,
            $this->email
        );

        $this->assertInstanceOf(ApiClient::class, $apiClient);
    }

    /**
     * @covers ::__construct
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     */
    public function testConstructor()
    {
        $apiClient = new ApiClient(
            $this->getGuzzleMock(),
            $this->logger,
            $this->subscriptionId,
            $this->accessToken,
            $this->company,
            $this->email
        );

        $this->assertInstanceOf(ApiClient::class, $apiClient);

        return $apiClient;
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     *
     * @depends testConstructor
     */
    public function testGetSuccess(ApiClient $apiClient)
    {
        $firstId = '8989985';

        $data = json_encode([
            ['id' => $firstId],
            ['id' => '2323232']
        ]);

        $apiClient = new ApiClient(
            $this->getGuzzleMock([
                new Response(200, [], $data),
            ]),
            $this->logger,
            $this->subscriptionId,
            $this->accessToken,
            $this->company,
            $this->email
        );

        $response = $apiClient->get('endpoint', []);

        $this->assertInternalType('array', $response);
        $this->assertCount(2, $response);
        $this->assertArrayHasKey('id', $response[0]);
        $this->assertEquals($firstId, $response[0]['id']);
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     * @depends testConstructor
     */
    public function testGetUnauthorizedFailure(ApiClient $apiClient)
    {
        $apiClient = new ApiClient(
            $this->getGuzzleMock([ new Response(401) ]),
            $this->logger,
            $this->subscriptionId,
            $this->accessToken,
            $this->company,
            $this->email
        );

        try {
            $response = $apiClient->get('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(401, $e->getErrorCode());
            $this->assertEquals('unauthorized', strtolower($e->getErrorMessage()));
        }
    }

    private function getGuzzleMock(array $responses = [])
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }
}
