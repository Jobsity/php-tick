<?php
namespace Jobsity\PhpTick\Test;

use Jobsity\PhpTick\Http\ApiClient;
use Jobsity\PhpTick\Tick;
use PHPUnit_Framework_TestCase;
use InvalidArgumentException;

/**
 * Class TickTest
 *
 * @package Jobsity\PhpTick\Test
 *
 * @coversDefaultClass Jobsity\PhpTick\Tick
 */
class TickTest extends PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        $this->subscriptionId = '456789';
        $this->accessToken = '2387654321234578';
        $this->company = 'Company';
        $this->email = 'some@company.com';
    }

    /**
     * @covers ::getInstance
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Tick::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testGetInstance()
    {
        $apiClient = Tick::getInstance(
            $this->subscriptionId,
            $this->accessToken,
            $this->company,
            $this->email
        );

        $this->assertInstanceOf(Tick::class, $apiClient);
    }

    /**
     * @covers ::getInstance
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Tick::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     *
     */
    public function testGetInstanceFailure()
    {
        try {
            $apiClient = Tick::getInstance(
                null,
                $this->accessToken,
                $this->company,
                $this->email
            );
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('you must specify a company, email address, access token and subscription id.',
                strtolower($e->getMessage()));
        }
    }



    /**
     * @covers ::__construct
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Tick::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     *
     */
    public function testConstructor()
    {
        $client = ApiClient::getInstance(
            $this->subscriptionId,
            $this->accessToken,
            $this->company,
            $this->email
        );

        $tick = new Tick($client);

        $this->assertInstanceOf(Tick::class, $tick);

        return $tick;
    }
}
