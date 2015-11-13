<?php
namespace Jobsity\PhpTick\Http\Exception;

use Exception;
use GuzzleHttp\Psr7\Response;

class ApiException extends Exception
{
    private $response;

    public function __construct(Response $response)
    {
        parent::__construct($response->getReasonPhrase());

        $this->response = $response;
    }

    public function getErrorCode()
    {
        return $this->response->getStatusCode();
    }

    public function getErrorMessage()
    {
        return $this->response->getReasonPhrase();
    }

    public function getResponse()
    {
        return $this->response;
    }
}
