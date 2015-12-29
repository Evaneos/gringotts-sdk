<?php

namespace Evaneos\Test\Gringotts\SDK;

use Evaneos\Gringotts\SDK\GringottsClient;
use Ramsey\Uuid\Uuid;

class GringottsClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GringottsClient
     */
    private $gringottsClient;

    public function setUp()
    {
        $this->gringottsClient = new GringottsClient();
    }

    public function testDoesPostWithCorrectParametersOnStore()
    {
        $this->assertTrue(Uuid::isValid($this->gringottsClient->store('bonjour.txt', 'bonjour')));
    }
}
