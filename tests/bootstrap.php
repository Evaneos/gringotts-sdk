<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

$loader->addPsr4('Evaneos\\Test\\Gringotts\\SDK\\', __DIR__);

\Phake::setClient(\Phake::CLIENT_PHPUNIT);
\Phake::setMockLoader(new \Phake_ClassGenerator_FileLoader('/tmp'));
