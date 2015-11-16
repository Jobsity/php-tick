<?php
namespace Jobsity\PhpTick\Tick;

use InvalidArgumentException;
use Jobsity\PhpTick\Http\ClientInterface;
use Jobsity\PhpTick\Http\ApiClient;

/**
 * Class Task
 *
 * @package Jobsity\PhpTick\Tick
 */
class Task
{
    /**
     * @var ApiClient Guzzle Api Client Handler
     */
    private $client;

    /**
     * Constructs Task.
     *
     * @param ClientInterface   $client     Guzzle Api Client Handler.
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get tasks list.
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->client->get('tasks');
    }

    /**
     * Get detail of one task.
     *
     * @param string    $taskId     Task id.
     *
     * @return mixed
     */
    public function get($taskId)
    {
        return $this->client->get('tasks/' . $taskId);
    }

    /**
     * Create task.
     *
     * @param string    $name        Task name.
     * @param string    $projectId   Project id which the task belongs.
     * @param string    $budget      Task's budget.
     * @param boolean   $billable    If it's billable.
     *
     * @return mixed
     */
    public function create($name, $projectId, $budget = null, $billable = true)
    {
        $params = [
            'name'  => $name,
            'project_id' => $projectId,
            'budget' => $budget,
            'billable' => $billable,
        ];

        return $this->client->post('tasks/', $params);
    }

    /**
     * Update task.
     *
     * @param string    $taskId      Task id.
     * @param string    $name        Task name.
     * @param string    $budget      Task's budget.
     * @param boolean   $billable    If it's billable.
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function update($taskId, $name = null, $budget = null, $billable = null)
    {
        if($name === null && $billable === null && $budget === null) {
            throw new InvalidArgumentException('You must specify at least one attribute for update.');
        }

        $params = [];

        if($name) {
            $params['name'] = $name;
        }
        if($billable) {
            $params['billable'] = $billable;
        }
        if($budget) {
            $params['budget'] = $budget;
        }

        return $this->client->put('tasks/' . $taskId, $params);
    }

    /**
     * Delete task.
     *
     * @param string    $taskId    Task id to be deleted.
     *
     * @return mixed
     */
    public function delete($taskId)
    {
        return $this->client->delete('tasks/' . $taskId);
    }
}
