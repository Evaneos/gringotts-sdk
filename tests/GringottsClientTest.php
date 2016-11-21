<?php

namespace Evaneos\Test\Gringotts\SDK;

use Evaneos\Gringotts\SDK\Exception\FileNotFoundException;
use Evaneos\Gringotts\SDK\Exception\UnableToDeleteFileException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Phake;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;

class GringottsClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GringottsTestClient
     */
    private $gringottsClient;

    public function setUp()
    {
        $this->gringottsClient = new GringottsTestClient('http://fake-endpoint');
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

        $this->assertTrue(Uuid::isValid($this->gringottsClient->store('bonjour')));
    }

    public function testThrowsExceptionOnUnsuccesfulDeleteReponse()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new ServerException('', Phake::mock(RequestInterface::class), new Response(500))
                ])
            ])
        );

        $this->setExpectedException(UnableToDeleteFileException::class);
        $this->gringottsClient->delete('9F3D2722-D454-41DA-85B7-94A9C6222126');
    }

    public function testThrowsNotFoundExceptionWhenResponseIs404OnDelete()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new ClientException('', Phake::mock(RequestInterface::class), new Response(404))
                ])
            ])
        );

        $this->setExpectedException(FileNotFoundException::class);
        $this->gringottsClient->delete('9F3D2722-D454-41DA-85B7-94A9C6222126');
    }

    public function testThrowsExceptionOnGuzzleTransferExceptionOnDelete()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new TransferException()
                ])
            ])
        );

        $this->setExpectedException(UnableToDeleteFileException::class);

        $this->gringottsClient->delete('927FA8B5-927D-4278-BFD2-3E3C7199E677');
    }

    public function testReturnsUuidOnSucessfulStoreWithIdResponse()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new Response(200, [], '{ "id": "9F3D2722-D454-41DA-85B7-94A9C6222126" }')
                ])
            ])
        );

        $this->assertTrue(Uuid::isValid($this->gringottsClient->storeWithId('9F3D2722-D454-41DA-85B7-94A9C6222126', 'bonjour')));
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

        $this->gringottsClient->store('bonjour');
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

        $this->gringottsClient->store('bonjour');
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

        $this->gringottsClient->store('bonjour');
    }

    /**
     * @expectedExceptionMessage The API response is invalid
     * @expectedException \Evaneos\Gringotts\SDK\Exception\InvalidStoreResponseException
     */
    public function testThrowsExceptionWhenStoreWithIdReponseIsInvalid()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new Response(200, [], '')
                ])
            ])
        );

        $this->gringottsClient->storeWithId('9F3D2722-D454-41DA-85B7-94A9C6222126', 'bonjour');
    }

    /**
     * @expectedExceptionMessage Missing field id
     * @expectedException \Evaneos\Gringotts\SDK\Exception\InvalidStoreResponseException
     */
    public function testThrowsExceptionWhenStoreWithIdReponseIsMissingTheIdField()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new Response(200, [], '{}')
                ])
            ])
        );

        $this->gringottsClient->storeWithId('9F3D2722-D454-41DA-85B7-94A9C6222126', 'bonjour');
    }

    /**
     * @expectedException \Evaneos\Gringotts\SDK\Exception\UnableToStoreFileException
     */
    public function testThrowsExceptionOnGuzzleTransferExceptionOnStoreWithId()
    {
        $this->gringottsClient->setClient(
            new Client([
                'handler' => new MockHandler([
                    new TransferException()
                ])
            ])
        );

        $this->gringottsClient->storeWithId('9F3D2722-D454-41DA-85B7-94A9C6222126', 'bonjour');
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
