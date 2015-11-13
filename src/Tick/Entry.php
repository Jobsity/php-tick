<?php
namespace Jobsity\PhpTick\Tick;

use Jobsity\PhpTick\Http\APIClient;
use Jobsity\PhpTick\Http\ClientInterface;
use Prophecy\Exception\InvalidArgumentException;

/**
 * Class Entry
 *
 * @package Jobsity\PhpTick\Tick
 */
class Entry
{
    /**
     * @var ApiClient Guzzle Api Client Handler
     */
    private $client;


    /**
     * Constructs Entry
     *
     * @param ClientInterface   $client     Guzzle Api Client Handler.
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get entries list filtered by the specified parameters
     *
     * @param string    $updatedAt      Modification date.
     * @param string    $startDate      Start date.
     * @param string    $endDate        End date.
     * @param boolean   $billable       Is billable.
     * @param string    $projectId      Project id which the entries belongs.
     * @param boolean   $billed         Is billed.
     * @param string    $taskId         Task id which the entry belongs.
     * @param string    $userId         User id who created the entry.
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function getList($updatedAt = null, $startDate = null, $endDate = null, $billable = null,
                            $projectId = null, $billed = null, $taskId = null, $userId = null)
    {
        if ($updatedAt === null && ($startDate === null || $endDate === null)) {
            throw new InvalidArgumentException('You must provide either updatedAt or a combination of startDate and endDate.');
        }

        $query = [
            'billable' => $billable,
            'project_id' => $projectId,
            'billed' => $billed,
            'task_id' => $taskId,
            'user_id' => $userId
        ];

        if ($updatedAt !== NULL) {
            $query['updated_at'] = $updatedAt;
        } else {
            $query['start_date'] = $startDate;
            $query['end_date'] = $endDate;
        }

        $this->client->get('entries', $query);
    }

    /**
     * @param string    $entryId    Entry id.
     */
    public function get($entryId)
    {
        $this->client->get('entries/' . $entryId);
    }

    /**
     * Create entry
     *
     * @param string    $taskId     Task id which the entry belongs.
     * @param string    $hours      Time in hours of the entry.
     * @param string    $date       Date when entry is created.
     * @param string    $notes      Notes of the entry.
     * @param string    $userId     User id who created the entry.
     *
     * @return mixed
     */
    public function create($hours, $date, $notes, $taskId = null, $userId = null)
    {
        $params = [
            'date'  => $date,
            'hours' => $hours,
            'notes' => $notes,
            'task_id' => $taskId,
            'user_id' => $userId
        ];
        $this->client->post('entries', $params);
    }

    /**
     * Update entry
     *
     * @param string    $hours      Time in hours of the entry.
     * @param string    $notes      Notes of the entry.
     * @param string    $entryId    Entry id to be modified.
     *
     * @return mixed
     */
    public function update($hours, $notes, $entryId)
    {
        $query = [
            'hours' => $hours,
            'notes' => $notes
        ];
        $this->client->put('entries/' . $entryId, $query);
    }

    /**
     * Delete entry
     *
     * @param string    $entryId    Entry id to be deleted.
     *
     * @return mixed
     */
    public function delete($entryId)
    {
        $this->client->delete('entries/' . $entryId);
    }
}
