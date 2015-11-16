<?php
namespace Jobsity\PhpTick\Tick;

use InvalidArgumentException;
use Jobsity\PhpTick\Http\ClientInterface;

/**
 * Class Project
 *
 * @package Jobsity\PhpTick\Tick
 */
class Project
{
    /**
     * @var \Jobsity\PhpTick\Http\ApiClient Guzzle Api Client Handler
     */
    private $client;

    /**
     * Constructs Project.
     *
     * @param \Jobsity\PhpTick\Http\ClientInterface   $client     Guzzle Api Client Handler.
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get opened projects list.
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->client->get('projects');
    }

    /**
     * Get closed projects list.
     *
     * @return mixed
     */
    public function getListClosed()
    {
        return $this->client->get('projects/closed');
    }

    /**
     * Get detail of one project.
     *
     * @param string    $projectId     Task id.
     *
     * @return mixed
     */
    public function get($projectId)
    {
        return $this->client->get('projects/' . $projectId);
    }

    /**
     * Get entries of an specific project.
     *
     * @param string    $projectId  Project Id.
     * @return mixed
     */
    public function getEntries($projectId)
    {
        return $this->client->get('projects/' . $projectId . '/entries');
    }

    /**
     * Create project.
     *
     * @param string    $name               Project name.
     * @param string    $clientId           Client id of the project.
     * @param string    $ownerId            Owner id of the project.
     * @param string    $budget             Project's budget.
     * @param boolean   $notifications      Notifications enabled.
     * @param boolean   $billable           If it's billable.
     * @param boolean   $recurring          If it's recurring.
     *
     * @return mixed
     */
    public function create(
        $name,
        $clientId,
        $ownerId,
        $budget = null,
        $notifications = true,
        $billable = true,
        $recurring = false
    ) {
        $params = [
            'name'  => $name,
            'client_id' => $clientId,
            'owner_id' => $ownerId,
            'budget' => $budget,
            'notifications' => $notifications,
            'billable' => $billable,
            'recurring' => $recurring
        ];

        return $this->client->post('projects/', $params);
    }

    /**
     * Update project.
     *
     * @param string    $projectId          Project id.
     * @param string    $name               Project name.
     * @param string    $clientId           Client id of the project.
     * @param string    $ownerId            Owner id of the project.
     * @param string    $budget             Project's budget.
     * @param boolean   $notifications      Notifications enabled.
     * @param boolean   $billable           If it's billable.
     * @param boolean   $recurring          If it's recurring.
     *
     * @throws InvalidArgumentException     Throws exception if there isn't at least one parameter to update
     *
     * @return mixed
     */
    public function update(
        $projectId,
        $name = null,
        $clientId = null,
        $ownerId = null,
        $budget = null,
        $notifications = null,
        $billable = null,
        $recurring = null
    ) {
        if (
            $name === null &&
            $clientId === null &&
            $ownerId === null &&
            $budget === null &&
            $notifications === null &&
            $billable === null &&
            $recurring === null
        ) {
            throw new InvalidArgumentException('You must specify at least one attribute for update.');
        }

        $params = [];

        if ($name) {
            $params['name'] = $name;
        }

        if ($clientId) {
            $params['client_id'] = $clientId;
        }

        if ($ownerId) {
            $params['owner_id'] = $ownerId;
        }

        if ($budget) {
            $params['budget'] = $budget;
        }

        if ($notifications) {
            $params['notifications'] = $notifications;
        }

        if ($billable) {
            $params['billable'] = $billable;
        }

        if ($recurring) {
            $params['recurring'] = $recurring;
        }

        return $this->client->put('tasks/' . $projectId, $params);
    }

    /**
     * Delete project.
     *
     * @param string    $projectId    Project id to be deleted.
     *
     * @return mixed
     */
    public function delete($projectId)
    {
        return $this->client->delete('projects/' . $projectId);
    }
}
