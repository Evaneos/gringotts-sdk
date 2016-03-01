<?php

namespace Evaneos\Gringotts\SDK\Exception;


class InvalidUuidException extends GringottsClientException
{
    /**
     * InvalidUuidException constructor.
     */
    public function __construct($notAnUuid)
    {
        parent::__construct("''{$notAnUuid}' is not a valid UUID");
    }
}
