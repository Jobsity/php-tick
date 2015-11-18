<?php
namespace Jobsity\PhpTick;

use InvalidArgumentException;
use Jobsity\PhpTick\Http\ApiClient;
use Jobsity\PhpTick\Http\ClientInterface;
use Jobsity\PhpTick\Tick\Entry;
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
     * @var \Jobsity\PhpTick\Http\ApiClient Guzzle Api Client Handler
     */
    private $client;

    /**
     * @var \Jobsity\PhpTick\Tick\Entry Entry Handler
     */
    public $entry;

    /**
     * @var \Jobsity\PhpTick\Tick\Task Task Handler
     */
    public $task;

    /**
     * @var \Jobsity\PhpTick\Tick\Project Project Handler
     */
    public $project;

    /**
     * Return an instance of the class.
     *
     * @param string   $subscriptionId   Subscription id of the user.
     * @param string   $accessToken      Access token of the user.
     * @param string   $company          User's company.
     * @param string   $email            User's email.
     *
     * @throws InvalidArgumentException  Throws exception if all parameters are missing
     *
     * @return \Jobsity\PhpTick\Tick    Created instance of the class.
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
     * @param \Jobsity\PhpTick\Http\ClientInterface $client   Guzzle client.
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;

        $this->entry = new Entry($this->client);
        $this->task = new Task($this->client);
        $this->project = new Project($this->client);
    }
}
