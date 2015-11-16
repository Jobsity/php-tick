<?php
namespace Jobsity\PhpTick\Test\Tick;

use PHPUnit_Framework_TestCase;
use InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use mef\Log\StandardLogger;
use Jobsity\PhpTick\Http\ApiClient;
use Jobsity\PhpTick\Tick\Task;

/**
 * Class TaskTest
 *
 * @package Jobsity\PhpTick\Test\Tick
 *
 * @coversDefaultClass Jobsity\PhpTick\Tick\Task
 */
class TaskTest extends PHPUnit_Framework_TestCase
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
     * @covers ::__construct
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     */
    public function testConstructor()
    {
        $task = $this->taskHandler();

        $this->assertInstanceOf(Task::class, $task);

        return $task;
    }

    /**
     * @covers ::getList
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     */
    public function testGetListSuccess()
    {
        $firstId = '8989985';

        $data = json_encode([
            ['id' => $firstId, 'name' => 'task test'],
            ['id' => '2323232', 'name' => 'some test']
        ]);

        $taskList = $this->taskHandler([ new Response(200, [], $data) ])->getList();

        $this->assertInternalType('array', $taskList);
        $this->assertCount(2, $taskList);
        $this->assertArrayHasKey('name', $taskList[0]);
        $this->assertEquals($firstId, $taskList[0]['id']);
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     */
    public function testGet()
    {
        $id = '3456843';
        $data = json_encode([
            ['id' => $id, 'name' => 'task test']
        ]);

        $task = $this->taskHandler([ new Response(200, [], $data) ])->get($id);

        $this->assertInternalType('array', $task);
        $this->assertCount(1, $task);
        $this->assertArrayHasKey('name', $task[0]);
        $this->assertEquals($id, $task[0]['id']);
    }

    /**
     * @covers ::create
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::post
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     */
    public function testCreate()
    {
        $name = 'some more';
        $projectId = '797899';
        $budget = '2.3';

        $data = json_encode([
            ['name' => $name, 'project_id' => $projectId, 'budget' => $budget]
        ]);

        $task = $this->taskHandler([ new Response(200, [], $data ) ])->create($name, $projectId, $budget);

        $this->assertInternalType('array', $task);
        $this->assertCount(1, $task);
        $this->assertEquals($name, $task[0]['name']);
    }

    /**
     * @covers ::update
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::put
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     */
    public function testUpdate()
    {
        $name = 'some more';
        $taskId = '797899';

        $data = json_encode([
            ['task' => $taskId, 'name' => $name]
        ]);

        $task = $this->taskHandler([ new Response(200, [], $data) ])->update($taskId, $name, true, '1.2');

        $this->assertInternalType('array', $task);
        $this->assertCount(1, $task);
        $this->assertEquals($taskId, $task[0]['task']);
    }

    /**
     * @covers ::update
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::put
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     */
    public function testUpdateFailure()
    {
        try {
            $task = $this->taskHandler()->update('797899');
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
     * @uses Jobsity\PhpTick\Tick\Task::__construct
     */
    public function testDelete()
    {
        $taskId = '3343434';

        $response = $this->taskHandler([ new Response(200) ])->delete($taskId);

        $this->assertEquals(200, $response->getStatusCode());
    }

    private function taskHandler(array $responses = [])
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

        $task = new Task($apiClient);

        return $task;
    }

}
