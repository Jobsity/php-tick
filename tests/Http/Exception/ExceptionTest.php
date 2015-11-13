<?php
namespace Jobsity\PhpTick\Test\Http\Exception;

use Exception;
use GuzzleHttp\Psr7\Response;
use Jobsity\PhpTick\Http\Exception\ApiException;
use PHPUnit_Framework_Error;
use PHPUnit_Framework_TestCase;

/**
 * Class ExceptionTest
 *
 * @package Jobsity\PhpTick\Test\Http\Exception
 *
 * @coversDefaultClass Jobsity\PhpTick\Http\Exception\ApiException
 */
class ApiClientTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->response = new Response(401);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorSuccess()
    {
        $exception = new ApiException($this->response);
        $this->assertInstanceOf(Exception::class, $exception);

        return $exception;
    }

    /**
     * @covers ::__construct
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructorFailure()
    {
        $exception = new ApiException([]);
    }

    /**
     * @covers ::getErrorCode
     * @covers ::getErrorMessage
     * @covers ::getResponse
     *
     * @depends testConstructorSuccess
     */
    public function testGetters(ApiException $exception)
    {
        $this->assertEquals(401, $exception->getErrorCode());
        $this->assertEquals('unauthorized', strtolower($exception->getErrorMessage()));
        $this->assertEquals($this->response, $exception->getResponse());
    }
}
