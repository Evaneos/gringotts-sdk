<?php

namespace Evaneos\Gringotts\SDK\Exception;

class InvalidStoreResponseException extends GringottsClientException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public static function missingField($field)
    {
        return new static("Missing field {$field}");
    }

    public static function invalidResponse()
    {
        return new static ("The API response is invalid");
    }
}
