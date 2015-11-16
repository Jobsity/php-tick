<?php
namespace Jobsity\PhpTick\Test\Tick;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Jobsity\PhpTick\Http\ApiClient;
use Jobsity\PhpTick\Tick\Entry;
use Jobsity\PhpTick\Tick\Project;
use mef\Log\StandardLogger;
use PHPUnit_Framework_TestCase;

/**
 * Class ProjectTest
 *
 * @package Jobsity\PhpTick\Test\Tick
 *
 * @coversDefaultClass Jobsity\PhpTick\Tick\Project
 */
class ProjectTest extends PHPUnit_Framework_TestCase
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
        $project = $this->projectHandler();

        $this->assertInstanceOf(Project::class, $project);

        return $project;
    }

    /**
     * @covers ::getList
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testGetListSuccess()
    {
        $firstId = '8989985';

        $data = json_encode([
            ['id' => $firstId, 'name' => 'project test'],
            ['id' => '2323232', 'name' => 'other project']
        ]);

        $projectList = $this->projectHandler([ new Response(200, [], $data )])->getList();

        $this->assertInternalType('array', $projectList);
        $this->assertCount(2, $projectList);
        $this->assertArrayHasKey('name', $projectList[0]);
        $this->assertEquals($firstId, $projectList[0]['id']);
    }

    /**
     * @covers ::getListClosed
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testGetListClosedSuccess()
    {
        $firstId = '8989985';

        $data = json_encode([
            ['id' => $firstId, 'name' => 'project test'],
            ['id' => '2323232', 'name' => 'other project']
        ]);

        $projectList = $this->projectHandler([ new Response(200, [], $data )])->getListClosed();

        $this->assertInternalType('array', $projectList);
        $this->assertCount(2, $projectList);
        $this->assertArrayHasKey('name', $projectList[0]);
        $this->assertEquals($firstId, $projectList[0]['id']);
    }

    /**
     * @covers ::get
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testGet()
    {
        $id = '3456843';
        $data = json_encode([
            ['id' => $id, 'name' => 'project test']
        ]);

        $project = $this->projectHandler([ new Response(200, [], $data) ])->get($id);

        $this->assertInternalType('array', $project);
        $this->assertCount(1, $project);
        $this->assertArrayHasKey('name', $project[0]);
        $this->assertEquals($id, $project[0]['id']);
    }

    /**
     * @covers ::getEntries
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::get
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testGetEntries()
    {
        $id = '3456843';
        $data = json_encode([
            ['id' => $id, 'name' => 'entry'],
            ['id' => '3598566', 'name' => 'some entry']
        ]);

        $project = $this->projectHandler([ new Response(200, [], $data) ])->getEntries($id);

        $this->assertInternalType('array', $project);
        $this->assertCount(2, $project);
        $this->assertArrayHasKey('name', $project[0]);
        $this->assertEquals($id, $project[0]['id']);
    }

    /**
     * @covers ::create
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::post
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     * @uses Jobsity\PhpTick\Tick\Project::update
     */
    public function testCreate()
    {
        $name = 'some more';
        $clientId = '797899';
        $ownerId = '2563';

        $data = json_encode([
            ['name' => $name, 'client_id' => $clientId, 'owner_id' => $ownerId]
        ]);

        $project = $this->projectHandler([ new Response(200, [], $data) ])->create($name, $clientId, $ownerId);

        $this->assertInternalType('array', $project);
        $this->assertCount(1, $project);
        $this->assertEquals($name, $project[0]['name']);
    }

    /**
     * @covers ::update
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::put
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testUpdate()
    {
        $projectId = '3434214';
        $name = 'some more';
        $clientId = '434343';
        $ownerId = '34343';
        $budget = '2.5';
        $notifications = true;
        $billable = true;
        $recurring = true;

        $data = json_encode([
            ['project' => $projectId, 'name' => $name]
        ]);

        $project = $this->projectHandler([ new Response(200, [], $data) ])->update($projectId, $name, $clientId,
            $ownerId, $budget, $notifications, $billable, $recurring);

        $this->assertInternalType('array', $project);
        $this->assertCount(1, $project);
        $this->assertEquals($projectId, $project[0]['project']);
    }

    /**
     * @covers ::update
     *
     * @uses Jobsity\PhpTick\Http\ApiClient::getInstance
     * @uses Jobsity\PhpTick\Http\ApiClient::__construct
     * @uses Jobsity\PhpTick\Http\ApiClient::put
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testUpdateFailure()
    {
        try {
            $project = $this->projectHandler()->update('797899');
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
     * @uses Jobsity\PhpTick\Tick\Project::__construct
     */
    public function testDelete()
    {
        $projectId = '3343434';

        $response = $this->projectHandler([ new Response(200) ])->delete($projectId);

        $this->assertEquals(200, $response->getStatusCode());
    }

    private function projectHandler(array $responses = [])
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

        $project = new Project($apiClient);

        return $project;
    }
}
