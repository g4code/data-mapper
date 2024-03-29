<?php

use G4\DataMapper\Engine\MySQL\Quote;
use G4\DataMapper\Common\SingleValue;

class QuoteTest extends \PHPUnit\Framework\TestCase
{

    public function testInt()
    {
        $quote = new Quote(new SingleValue(101));
        $value = (string) $quote;
        $this->assertEquals('101', $value);
        $this->assertTrue(is_string($value));
    }

    public function testFloat()
    {
        $quote = new Quote(new SingleValue(1.01));
        $value = (string) $quote;
        $this->assertEquals('1.010000', $value);
        $this->assertTrue(is_string($value));
    }

    public function testString()
    {
        $quote = new Quote(new SingleValue("lorem ipsum \n \r \\ ' \""));
        $value = (string) $quote;
        $this->assertEquals("'lorem ipsum \\n \\r \\\ \' \\\"'", $value);
        $this->assertTrue(is_string($value));
    }

    public function testArray()
    {
        $quote = new Quote(new SingleValue(['101', 'lorem ipsum',  1.01]));
        $value = (string) $quote;
        $this->assertEquals("('101', 'lorem ipsum', '1.01')", $value);
        $this->assertTrue(is_string($value));
    }
}
