<?php

use G4\DataMapper\Common\RangeValue;

class RangeValueTest extends PHPUnit_Framework_TestCase
{
    private $rangeValueObject;

    protected function setup()
    {
        $this->rangeValueObject = new RangeValue(5,18);
    }

    protected function tearDown()
    {
        $this->rangeValueObject = null;
    }

    public function testMin()
    {
        $this->assertEquals(5, $this->rangeValueObject->getMin());
    }

    public function testMax()
    {
        $this->assertEquals(18, $this->rangeValueObject->getMax());
    }
}
