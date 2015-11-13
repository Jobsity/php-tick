<?php
namespace Jobsity\PhpTick\Test\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
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
        $apiClient = $this->getClientApi();

        $this->assertInstanceOf(ApiClient::class, $apiClient);

        return $apiClient;
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     *
     */
    public function testGetSuccess()
    {
        $firstId = '8989985';

        $data = json_encode([
            ['id' => $firstId],
            ['id' => '2323232']
        ]);

        $apiClient = $this->getClientApi([new Response(200, [], $data)]);

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
     */
    public function testGetUnauthorizedFailure()
    {
        $apiClient = $this->getClientApi([ new Response(401) ]);

        try {
            $response = $apiClient->get('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(401, $e->getErrorCode());
            $this->assertEquals('unauthorized', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testGetServerFailure()
    {
        $apiClient = $this->getClientApi([ new Response(500) ]);

        try {
            $response = $apiClient->get('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('internal server error', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testGetRequestFailure()
    {
        $apiClient = $this->getClientApi([ new RequestException("Something went wrong", new Request('GET', 'endpoint'),
            new Response(500, [], null, null, 'Something went wrong') )]);

        try {
            $response = $apiClient->get('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('something went wrong', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testGetRequestGeneralFailure()
    {
        $apiClient = $this->getClientApi([new RequestException("Something went wrong", new Request('GET', 'endpoint'))]);

        try {
            $response = $apiClient->get('endpoint', []);
        } catch (Exception $e) {
            $this->assertEquals('something went wrong', strtolower($e->getMessage()));
        }
    }

    /**
     * @covers ::post
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     *
     */
    public function testPostSuccess()
    {
        $taskId = '34344443';
        $data = json_encode([
            ['date' => '2015-11-10'],
            ['hours' => '5'],
            ['task_id' => $taskId],
            ['notes' => 'some test v2']
        ]);

        $apiClient = $this->getClientApi([new Response(200, [], $data)]);

        $response = $apiClient->post('endpoint', []);

        $this->assertInternalType('array', $response);
        $this->assertCount(4, $response);
        $this->assertArrayHasKey('task_id', $response[2]);
        $this->assertEquals($taskId, $response[2]['task_id']);
    }

    /**
     * @covers ::post
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testPostUnauthorizedFailure()
    {
        $apiClient = $this->getClientApi([ new Response(401) ]);

        try {
            $response = $apiClient->post('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(401, $e->getErrorCode());
            $this->assertEquals('unauthorized', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::post
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testPostServerFailure()
    {
        $apiClient = $this->getClientApi([ new Response(500) ]);

        try {
            $response = $apiClient->post('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('internal server error', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::post
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testPostRequestFailure()
    {
        $apiClient = $this->getClientApi([ new RequestException("Something went wrong", new Request('POST', 'endpoint'),
            new Response(500, [], null, null, 'something went wrong'))]);

        try {
            $response = $apiClient->post('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('something went wrong', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::post
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     *
     */
    public function testPostRequestGeneralFailure()
    {
        $apiClient = $this->getClientApi([new RequestException("Something went wrong", new Request('POST', 'endpoint'))]);

        try {
            $response = $apiClient->post('endpoint', []);
        } catch (Exception $e) {
            $this->assertEquals('something went wrong', strtolower($e->getMessage()));
        }
    }

    /**
     * @covers ::put
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     *
     */
    public function testPutSuccess()
    {
        $data = json_encode([
            ['hours' => '2'],
            ['notes' => 'some test v2']
        ]);

        $apiClient = $this->getClientApi([new Response(204, [], $data)]);

        $response = $apiClient->put('endpoint', []);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('no content', strtolower($response->getReasonPhrase()));
    }

    /**
     * @covers ::put
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testPutUnauthorizedFailure()
    {
        $apiClient = $this->getClientApi([ new Response(401) ]);

        try {
            $response = $apiClient->put('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(401, $e->getErrorCode());
            $this->assertEquals('unauthorized', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::put
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testPutServerFailure()
    {
        $apiClient = $this->getClientApi([ new Response(500) ]);

        try {
            $response = $apiClient->put('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('internal server error', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::put
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testPutRequestFailure()
    {
        $apiClient = $this->getClientApi([ new RequestException("Something went wrong", new Request('PUT', 'endpoint'),
            new Response(500, [], null, null, 'something went wrong'))]);

        try {
            $response = $apiClient->put('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('something went wrong', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::put
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testPutRequestGeneralFailure()
    {
        $apiClient = $this->getClientApi([new RequestException("Something went wrong", new Request('PUT', 'endpoint'))]);

        try {
            $response = $apiClient->put('endpoint', []);
        } catch (Exception $e) {
            $this->assertEquals('something went wrong', strtolower($e->getMessage()));
        }
    }

    /**
     * @covers ::delete
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     *
     */
    public function testDeleteSuccess()
    {
        $apiClient = $this->getClientApi([ new Response(204) ]);

        $response = $apiClient->delete('endpoint', []);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('no content', strtolower($response->getReasonPhrase()));
    }

    /**
     * @covers ::delete
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testDeleteUnauthorizedFailure()
    {
        $apiClient = $this->getClientApi([ new Response(401) ]);

        try {
            $response = $apiClient->delete('endpoint');
        } catch (ApiException $e) {
            $this->assertEquals(401, $e->getErrorCode());
            $this->assertEquals('unauthorized', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::delete
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testDeleteServerFailure()
    {
        $apiClient = $this->getClientApi([ new Response(500) ]);

        try {
            $response = $apiClient->delete('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('internal server error', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::delete
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testDeleteRequestFailure()
    {
        $apiClient = $this->getClientApi([ new RequestException("Something went wrong", new Request('PUT', 'endpoint'),
            new Response(500, [], null, null, 'something went wrong'))]);

        try {
            $response = $apiClient->delete('endpoint');
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('something went wrong', strtolower($e->getErrorMessage()));
        }
    }

    /**
     * @covers ::delete
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\Exception\ApiException
     *
     */
    public function testDeleteRequestGeneralFailure()
    {
        $apiClient = $this->getClientApi([new RequestException("Something went wrong", new Request('DELETE', 'endpoint'))]);

        try {
            $response = $apiClient->delete('endpoint', []);
        } catch (ApiException $e) {
            $this->assertEquals(500, $e->getErrorCode());
            $this->assertEquals('internal server error', strtolower($e->getErrorMessage()));
        } catch (Exception $e) {
            $this->assertEquals('something went wrong', strtolower($e->getMessage()));
        }
    }

    private function getClientApi(array $responses = [])
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handler]);

        $apiClient = new ApiClient(
            $guzzleClient,
            $this->logger,
            $this->subscriptionId,
            $this->accessToken,
            $this->company,
            $this->email
        );

        return $apiClient;
    }
}
