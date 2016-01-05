<?php

namespace Evaneos\Gringotts\SDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\StreamInterface;

class GringottsClient
{

    const BASE_URL = 'http://localhost:8000/';
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => static::BASE_URL]);
    }

    /**
     * Store a file into Gringotts.
     *
     * @param $filename
     * @param string|resource|StreamInterface $data
     * @return string uuid of the stored file
     */
    public function store($filename, $data)
    {
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

    }

    /**
     * Get a file from Gringotts.
     *
     * @param string $uuid The file uuid
     * @return StreamInterface The file stream
     * @throws GringottsClientException
     */
    public function get($uuid)
    {
        try {
            $response = $this->client->request('GET',"/{$uuid}");
        } catch(ClientException $e) {
            throw new GringottsClientException($e);
        }
        var_dump($response);
        return $response->getBody();
    }
}
