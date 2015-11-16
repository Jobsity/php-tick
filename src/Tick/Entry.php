<?php
namespace Jobsity\PhpTick\Tick;

use Jobsity\PhpTick\Http\APIClient;
use Jobsity\PhpTick\Http\ClientInterface;
use InvalidArgumentException;

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
     * Get entries list filtered by the specified parameters.
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

        if ($updatedAt !== null) {
            $query['updated_at'] = $updatedAt;
        } else {
            $query['start_date'] = $startDate;
            $query['end_date'] = $endDate;
        }

        return $this->client->get('entries', $query);
    }

    /**
     * Get detail of one entry.
     *
     * @param string    $entryId    Entry id.
     *
     * @return mixed
     */
    public function get($entryId)
    {
        return $this->client->get('entries/' . $entryId);
    }

    /**
     * Create entry.
     *
     * @param string    $taskId     Task id which the entry belongs.
     * @param string    $hours      Time in hours of the entry.
     * @param string    $date       Entry's date.
     * @param string    $notes      Notes of the entry.
     * @param string    $userId     User id who created the entry.
     *
     * @return mixed
     */
    public function create($hours, $date, $notes, $taskId, $userId = null)
    {
        $params = [
            'date'  => $date,
            'hours' => $hours,
            'notes' => $notes,
            'task_id' => $taskId,
            'user_id' => $userId
        ];

        return $this->client->post('entries', $params);
    }

    /**
     * Update entry.
     *
     * @param string    $entryId    Entry id.
     * @param string    $hours      Time in hours of the entry.
     * @param string    $notes      Notes of the entry.
     * @param string    $date       Entry's date.
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function update($entryId, $hours = null, $notes = null, $date = null)
    {
        if($hours === null && $notes === null && $date === null) {
            throw new InvalidArgumentException('You must specify at least one attribute for update.');
        }

        $query = [];
        if($hours) {
            $query['hours'] = $hours;
        }
        if($notes) {
            $query['notes'] = $notes;
        }
        if($date) {
            $query['date'] = $date;
        }

        return $this->client->put('entries/' . $entryId, $query);
    }

    /**
     * Delete entry.
     *
     * @param string    $entryId    Entry id to be deleted.
     *
     * @return mixed
     */
    public function delete($entryId)
    {
        return $this->client->delete('entries/' . $entryId);
    }
}
