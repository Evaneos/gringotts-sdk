<?php

namespace Evaneos\Gringotts\SDK\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class FileNotFoundException extends GringottsClientException
{
    public function __construct(UuidInterface $uuid, Exception $previous = null)
    {
        parent::__construct("File with UUID {$uuid} was not found");
    }
}
