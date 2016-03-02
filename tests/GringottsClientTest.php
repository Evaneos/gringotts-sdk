<?php

namespace Evaneos\Test\Gringotts\SDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Ramsey\Uuid\Uuid;

class GringottsClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GringottsClient
     */
    private $gringottsClient;

    public function setUp()
    {
        $this->gringottsClient = new GringottsClient();
    }

    public function testReturnsUuidOnSucessfulStoreResponse()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new Response(200, [], '{ "id": "9F3D2722-D454-41DA-85B7-94A9C6222126" }')
                ])
            ])
        );

        $this->assertTrue(Uuid::isValid($this->gringottsClient->store('toto.txt', 'bonjour')));
    }

    public function testReturnsResponseBodyOnSucessfulGetResponse()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new Response(200, [], 'conteeeeeeeeents !!!!!!!')
                ])
            ])
        );

        $this->assertEquals(
            'conteeeeeeeeents !!!!!!!',
            $this->gringottsClient->get('78677435-2A02-4170-B6F6-D49CBE46D6A2')
        );
    }

    /**
     * @expectedExceptionMessage The API response is invalid
     * @expectedException \Evaneos\Gringotts\SDK\Exception\InvalidStoreResponseException
     */
    public function testThrowsExceptionWhenStoreReponseIsInvalid()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new Response(200, [], '')
                ])
            ])
        );

        $this->gringottsClient->store('toto.txt', 'bonjour');
    }

    /**
     * @expectedExceptionMessage Missing field id
     * @expectedException \Evaneos\Gringotts\SDK\Exception\InvalidStoreResponseException
     */
    public function testThrowsExceptionWhenStoreReponseIsMissingTheIdField()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new Response(200, [], '{}')
                ])
            ])
        );

        $this->gringottsClient->store('toto.txt', 'bonjour');
    }

    /**
     * @expectedException \Evaneos\Gringotts\SDK\Exception\UnableToStoreFileException
     */
    public function testThrowsExceptionOnGuzzleTransferExceptionOnStore()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new TransferException()
                ])
            ])
        );

        $this->gringottsClient->store('toto.txt', 'bonjour');
    }

    /**
     * @expectedException \Evaneos\Gringotts\SDK\Exception\UnableToGetFileException
     */
    public function testThrowsExceptionOnGuzzleTransferExceptionOnGet()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new TransferException()
                ])
            ])
        );

        $this->gringottsClient->get('927FA8B5-927D-4278-BFD2-3E3C7199E677');
    }

    /**
     * @expectedException \Evaneos\Gringotts\SDK\Exception\InvalidUuidException
     */
    public function testThrowsExceptionOnGetWithAnInvalidUuid()
    {
        $this->gringottsClient->get('random string which is clearly not a UUID');
    }
}
