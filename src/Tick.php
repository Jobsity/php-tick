<?php
namespace Jobsity\PhpTick;

use Jobsity\PhpTick\Http\ApiClient;
use Jobsity\PhpTick\Http\ClientInterface;
use Jobsity\PhpTick\Tick\Entry;
use InvalidArgumentException;
use Jobsity\PhpTick\Tick\Project;
use Jobsity\PhpTick\Tick\Task;


/**
 * Class Tick
 *
 * @package Jobsity\PhpTick
 */
class Tick
{
    /**
     * @var ApiClient Guzzle Api Client Handler
     */
    private $client;

    /**
     * @var Entry Entry Handler
     */
    public $entry;

    public $task;

    public $project;

    /**
     * Return an instance of the class.
     *
     * @param string   $subscriptionId   Subscription id of the user.
     * @param string   $accessToken      Access token of the user.
     * @param string   $company          User's company.
     * @param string   $email            User's email.
     *
     * @throw InvalidArgumentException
     *
     * @return Tick    Created instance of the class.
     */
    public static function getInstance($subscriptionId, $accessToken, $company, $email)
    {
        if (!$subscriptionId || !$accessToken || !$company || !$email) {
            throw new InvalidArgumentException('You must specify a company, email address, access token and subscription id.');
        }

        $client = ApiClient::getInstance($subscriptionId, $accessToken, $company, $email);

        return new self($client);
    }

    /**
     * Constructs Tick.
     *
     * @param ClientInterface $client   Guzzler client.
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;

        $this->entry = new Entry($this->client);
        $this->task = new Task($this->client);
        $this->project = new Project($this->client);
    }
}
