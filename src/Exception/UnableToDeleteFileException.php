<?php


namespace Evaneos\Gringotts\SDK\Exception;


use Exception;
use Ramsey\Uuid\UuidInterface;

class UnableToDeleteFileException extends GringottsClientException
{
    /**
     * UnableToDeleteFileException constructor.
     * @param UuidInterface $uuid
     * @param Exception $previous
     */
    public function __construct(UuidInterface $uuid, Exception $previous = null)
    {
        parent::__construct("Unable to delete file with uuid {$uuid}", 0, $previous);
    }
}
