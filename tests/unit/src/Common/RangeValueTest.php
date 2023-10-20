<?php

use G4\DataMapper\Common\RangeValue;

class RangeValueTest extends \PHPUnit\Framework\TestCase
{
    private $rangeValueObject;

    protected function setup(): void
    {
        $this->rangeValueObject = new RangeValue(5,18);
    }

    protected function tearDown(): void
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

    public function testIsEmpty()
    {
        $this->assertEquals(true, (new RangeValue(null, null))->isEmpty());
    }

    public function testIsMinNull()
    {
        $this->assertEquals(true, (new RangeValue(null, 10))->isMinNull());
    }

    public function testIsMaxNull()
    {
        $this->assertEquals(true, (new RangeValue(1, null))->isMaxNull());
    }

    public function testMinWithNullValue()
    {
        $this->assertEquals(0, (new RangeValue(null, 0))->getMin());
        $this->assertFalse((new RangeValue(null, 0))->isMinNull());
        $this->assertTrue((new RangeValue(null, 5))->isMinNull());
        $this->assertTrue((new RangeValue(null, 0))->isMaxNull());
    }

    public function testMaxWithNullValue()
    {
        $this->assertEquals(0, (new RangeValue(0, null))->getMax());
        $this->assertTrue((new RangeValue(0, null))->isMaxNull());
        $this->assertTrue((new RangeValue(5, null))->isMaxNull());
    }

}
