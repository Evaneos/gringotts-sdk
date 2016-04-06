<?php

namespace Evaneos\Gringotts\SDK;

use Evaneos\Gringotts\SDK\Exception\InvalidStoreResponseException;
use Evaneos\Gringotts\SDK\Exception\InvalidUuidException;
use Evaneos\Gringotts\SDK\Exception\UnableToGetFileException;
use Evaneos\Gringotts\SDK\Exception\UnableToStoreFileException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\StreamInterface;
use Ramsey\Uuid\Uuid;

class GringottsClient
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct($endpoint)
    {
        $this->client = new Client(['base_uri' => $endpoint]);
    }

    /**
     * Store a file into Gringotts.
     *
     * @param string|resource|StreamInterface $data
     * @return string uuid of the stored file
     * @throws InvalidStoreResponseException
     * @throws UnableToStoreFileException
     */
    public function store($data)
    {
        return $this->processRequest(function() use($data) {
            return $this->client->request('POST', "/", [ 'body' => $data ]);
        });
    }

    public function storeWithId($id, $data)
    {
        return $this->processRequest(function() use($id, $data) {
            return $this->client->request('PUT', "/{$id}", [ 'body' => $data ]);
        });
    }

    private function processRequest(callable $request)
    {
        try {
            $response = $request();

            $body = json_decode($response->getBody(), true);

            if($body === null) {
                throw InvalidStoreResponseException::invalidResponse();
            }

            if(!array_key_exists('id', $body)) {
                throw InvalidStoreResponseException::missingField('id');
            }

            return $body['id'];
        } catch(TransferException $e) {
            throw new UnableToStoreFileException($e);
        }
    }

    /**
     * Get a file from Gringotts.
     *
     * @param string $uuid The file uuid
     * @return StreamInterface The file stream
     * @throws InvalidUuidException
     * @throws UnableToGetFileException
     */
    public function get($uuid)
    {
        try {
            if(!Uuid::isValid($uuid)) {
                throw new InvalidUuidException($uuid);
            }

            $response = $this->client->request('GET', "/{$uuid}");

            return $response->getBody();
        } catch(TransferException $e) {
            throw new UnableToGetFileException(Uuid::fromString($uuid), $e);
        }
    }
}
