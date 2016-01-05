<?php

namespace Evaneos\Gringotts\SDK;

class GringottsClientException extends \Exception
{
    /**
     * GringottsClientException constructor.
     * @param \GuzzleHttp\Exception\ClientException $e
     */
    public function __construct($e)
    {
        $response = json_decode($e->getResponse(), true);
        parent::__construct(isset($response['error']) ? $response['error'] : $e->getMessage());
    }
}