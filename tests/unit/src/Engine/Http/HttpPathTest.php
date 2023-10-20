<?php

use G4\DataMapper\Engine\Http\HttpPath;
use G4\DataMapper\Exception\HttpPathException;

class HttpPathTest extends \PHPUnit\Framework\TestCase
{

    public function testValidValueWithOnePart()
    {
        $httpPath = new HttpPath('api');

        $this->assertEquals('api', (string) $httpPath);
    }

    public function testValidValueWithMultipleParts()
    {
        $httpPath = new HttpPath('api', 'messages', 'inbox');

        $this->assertEquals('api/messages/inbox', (string) $httpPath);
    }

    public function testHttpPathException()
    {
        $this->expectException(HttpPathException::class);

        new HttpPath();
    }
}
