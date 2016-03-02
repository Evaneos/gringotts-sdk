<?php

namespace Evaneos\Test\Gringotts\SDK;

use GuzzleHttp\ClientInterface;

class GringottsTestClient extends \Evaneos\Gringotts\SDK\GringottsClient
{
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }
}
