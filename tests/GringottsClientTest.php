<?php

namespace Evaneos\Test\Gringotts\SDK;

use Evaneos\Gringotts\SDK\GringottsClient;
use GuzzleHttp\Client;
use Phake;
use Psr\Http\Message\ResponseInterface;

class GringottsClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GringottsClient
     */
    private $gringottsClient;
    private $guzzleClient;

    public function setUp()
    {
        $this->guzzleClient = Phake::mock(Client::class);
        $this->gringottsClient = new GringottsClient($this->guzzleClient);
    }

    public function testDoesPostWithCorrectParametersOnStore()
    {
        $response = Phake::mock(ResponseInterface::class);

        Phake::when($this->guzzleClient)->request(Phake::anyParameters())
            ->thenReturn($response);

        Phake::when($response)->getBody()
            ->thenReturn('{"id": "fake-id"}');

        $id = $this->gringottsClient->store('bonjour');

        $this->assertEquals('fake-id', $id);
    }
}
