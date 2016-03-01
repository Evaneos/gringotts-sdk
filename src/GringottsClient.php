<?php

namespace Evaneos\Gringotts\SDK;

use Evaneos\Gringotts\SDK\Exception\InvalidUuidException;
use Evaneos\Gringotts\SDK\Exception\UnableToGetFileException;
use Evaneos\Gringotts\SDK\Exception\UnableToStoreFileException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\StreamInterface;
use Ramsey\Uuid\Uuid;

class GringottsClient
{
    const BASE_URL = 'http://localhost:8000/';

    /**
     * @var Client
     */
    protected $client;

    public function __construct($endpoint = null)
    {
        $this->client = new Client(['base_uri' => $endpoint ?: static::BASE_URL]);
    }

    /**
     * Store a file into Gringotts.
     *
     * @param $filename
     * @param string|resource|StreamInterface $data
     * @return string uuid of the stored file
     * @throws UnableToStoreFileException
     */
    public function store($filename, $data)
    {
        try {
            $response = $this->client->request('POST','/', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'filename' => $filename,
                        'contents' => $data
                    ]
                ]
            ]);

            return json_decode($response->getBody(), true)['id'];
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
