<?php

namespace Evaneos\Gringotts\SDK\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class UnableToGetFileException extends GringottsClientException
{
    public function __construct(UuidInterface $uuid, Exception $previous = null)
    {
        parent::__construct("Unable to get file with uuid {$uuid}", 0, $previous);
    }
}
