<?php
namespace Jobsity\PhpTick\Api;

use Jobsity\PhpTick\Http\APIClient;

class TickspotAPI
{

    private $client;

    /**
     * Default constructor.
     */
    public function __construct($subscription_id, $access_token, $company, $email)
    {
        // store parameters
        if (!$subscription_id || !$access_token || !$company || !$email) {
            throw new \Exception('You must specify a company, email address, access token and subscrption id.');
        }

        $this->client = new APIClient($subscription_id, $access_token, $company, $email);
    }



    public function getEntries($updated_at = NULL, $start_date = NULL, $end_date = NULL,
                               $entry_billable = NULL, $project_id = NULL, $task_id = NULL,
                               $user_id = NULL, $billed = NULL)
    {

        if ($updated_at === NULL && ($start_date === NULL || $end_date === NULL)) {
            throw new \Exception('You must provide either updated_at or a combination of start_date and end_date.');
        }

        $query = array(
            'entry_billable' => $entry_billable,
            'project_id' => $project_id,
            'task_id' => $task_id,
            'user_id' => $user_id,
            'billed' => $billed
        );

        // determine which required params to add
        if ($updated_at !== NULL) {
            $query['updated_at'] = $updated_at;
        } else {
            $query['start_date'] = $start_date;
            $query['end_date'] = $end_date;
        }

        $this->client->get('entries', $query);
    }

     public function createEntry($task_id = '2101774', $hours = '6', $date='2015-11-11' , $notes = 'some test', $user_id = null)
     {
        $params = [
            'date' => $date,
            'hours' => $hours,
            'task_id' => $task_id,
            'notes' => $notes,
            'user_id' => $user_id
        ];

        $this->client->post('entries', $params);
    }

    public function updateEntry(){
        $query = array(
            'hours' => '2',
            'notes' => 'some test v2'
        );

        $this->client->put('entries/48894668', $query);
    }

    public function deleteEntry(){
        $this->client->delete('entries/48894447');
    }
}
