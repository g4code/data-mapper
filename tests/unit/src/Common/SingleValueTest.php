<?php

use G4\DataMapper\Common\SingleValue;

class SingleValueTest extends PHPUnit_Framework_TestCase
{
    public function testIsInt()
    {
        $this->assertEquals(true, (new SingleValue(5))->isInteger());
    }

    public function testIsFloat()
    {
        $this->assertEquals(true, (new SingleValue(3.18))->isFloat());
    }

    public function testIsString()
    {
        $this->assertEquals(true, (new SingleValue('test'))->isString());
    }

    public function testIsArray()
    {
        $this->assertEquals(true, (new SingleValue([1,2,3]))->isArray());
    }
}
