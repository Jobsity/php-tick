<?php
namespace Jobsity\PhpTick\Test\Tick;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Jobsity\PhpTick\Http\ApiClient;
use Jobsity\PhpTick\Tick\Entry;
use mef\Log\StandardLogger;
use PHPUnit_Framework_TestCase;

/**
 * Class EntryTest
 *
 * @package Jobsity\PhpTick\Test\Tick
 *
 * @coversDefaultClass Jobsity\PhpTick\Tick\Entry
 */
class EntryTest extends PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        $this->subscriptionId = '456789';
        $this->accessToken = '2387654321234578';
        $this->company = 'Company';
        $this->email = 'some@company.com';
        $this->logger = $this->getMock(StandardLogger::class);
    }

    /**
     * @covers ::__construct
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     */
    public function testConstructor()
    {
        $entry = $this->entryHandler();

        $this->assertInstanceOf(Entry::class, $entry);

        return $entry;
    }

    /**
     * @covers ::getList
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::create
     */
    public function testGetListSuccess()
    {
        $date = '2015-11-11';

        $data = json_encode([
            ['id' => '2521463', 'date' => $date],
            ['id' => '2323232', 'date' => '2015-11-12']
        ]);

        $entryList = $this->entryHandler([ new Response(200, [], $data) ])->getList($date);

        $this->assertInternalType('array', $entryList);
        $this->assertCount(2, $entryList);
        $this->assertArrayHasKey('date', $entryList[0]);
        $this->assertEquals($date, $entryList[0]['date']);
    }

    /**
     * @covers ::getList
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::create
     */
    public function testGetListPeriodSuccess()
    {
        $date = '2015-11-11';

        $data = json_encode([
            ['id' => '2521463', 'date' => $date],
            ['id' => '2323232', 'date' => '2015-11-12']
        ]);

        $entryList = $this->entryHandler([ new Response(200, [], $data )])->getList(null, $date, '2015-11-12');

        $this->assertInternalType('array', $entryList);
        $this->assertCount(2, $entryList);
        $this->assertArrayHasKey('date', $entryList[0]);
        $this->assertEquals($date, $entryList[0]['date']);
    }

    /**
     * @covers ::getList
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     */
    public function testGetListFailure()
    {
        try {
            $entryList = $this->entryHandler([ new Response(401) ])->getList();
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('you must provide either updatedat or both startdate and enddate.',
                strtolower($e->getMessage()));
        }
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::create
     */
    public function testGet()
    {
        $id = '3456843';
        $data = json_encode([
            ['id' => $id, 'date' => '2015-11-12', 'note' => 'testing']
        ]);

        $entry = $this->entryHandler([ new Response(200, [], $data) ])->get($id);

        $this->assertInternalType('array', $entry);
        $this->assertCount(1, $entry);
        $this->assertArrayHasKey('date', $entry[0]);
        $this->assertEquals($id, $entry[0]['id']);
    }

    /**
     * @covers ::create
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::post
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     * @uses Jobsity\PhpTick\Tick\Entry::delete
     */
    public function testCreate()
    {
        $hours = 5;
        $date = '2015-11-10';
        $notes = 'notes';
        $taskId = '3343434';

        $data = json_encode([
            ['hours' => $hours, 'date' => $date, 'notes' => $notes, 'task' => $taskId]
        ]);

        $entry = $this->entryHandler([ new Response(200, [], $data )])->create($hours, $date, $notes, $taskId);

        $this->assertInternalType('array', $entry);
        $this->assertCount(1, $entry);
        $this->assertEquals($date, $entry[0]['date']);
    }

    /**
     * @covers ::update
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::put
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     */
    public function testUpdate()
    {
        $hours = 5;
        $notes = 'notes';
        $date = '2015-11-14';
        $entryId = '3343434';

        $data = json_encode([
            ['hours' => $hours, 'notes' => $notes, 'entry' => $entryId]
        ]);

        $entry = $this->entryHandler([ new Response(200, [], $data )])->update($entryId, $hours, $notes, $date);

        $this->assertInternalType('array', $entry);
        $this->assertCount(1, $entry);
        $this->assertEquals($entryId, $entry[0]['entry']);
    }

    /**
     * @covers ::update
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::put
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     */
    public function testUpdateFailure()
    {
        try {
            $entry = $this->entryHandler()->update('458158');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('you must specify at least one attribute for update.', strtolower($e->getMessage()));
        }
    }

    /**
     * @covers ::delete
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::delete
     * @uses Jobsity\PhpTick\Tick\Entry::__construct
     */
    public function testDelete()
    {
        $entryId = '3343434';

        $response = $this->entryHandler([ new Response(200) ])->delete($entryId);

        $this->assertEquals(200, $response->getStatusCode());
    }

    private function entryHandler(array $responses = [])
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

        $entry = new Entry($apiClient);

        return $entry;
    }
}
