<?php

namespace Evaneos\Gringotts\SDK\Exception;

use Exception;

class UnableToStoreFileException extends GringottsClientException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Unable to store file', 0, $previous);
    }
}
