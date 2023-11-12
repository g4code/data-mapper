<?php

use G4\DataMapper\Common\SingleValue;

class SingleValueTest extends \PHPUnit\Framework\TestCase
{
    public function testToStringWithStringValue()
    {
        $this->assertEquals('test', new SingleValue('test'));
    }

    public function testToStringWithNumericValue()
    {
        $this->assertEquals('5', new SingleValue(5));
    }

    public function testToStringWithArrayValue()
    {
        $this->assertEquals('5,6,7', new SingleValue([5, 6, 7]));
    }

    public function testIsEmptyWithNullValue()
    {
        $this->assertEquals(true, (new SingleValue(null))->isEmpty());
    }

    public function testIsEmptyWithEmptyArrayValue()
    {
        $this->assertEquals(true, (new SingleValue([]))->isEmpty());
    }
}
