<?php

use G4\DataMapper\Engine\MySQL\Quote;

class QuoteTest extends PHPUnit_Framework_TestCase
{

    public function testInt()
    {
        $quote = new Quote(101);
        $value = (string) $quote;
        $this->assertEquals('101', $value);
        $this->assertTrue(is_string($value));
    }

    public function testFloat()
    {
        $quote = new Quote(1.01);
        $value = (string) $quote;
        $this->assertEquals('1.01', $value);
        $this->assertTrue(is_string($value));
    }

    public function testString()
    {
        $quote = new Quote("lorem ipsum \n \r \\ ' \"");
        $value = (string) $quote;
        $this->assertEquals("'lorem ipsum \\n \\r \\\ \' \\\"'", $value);
        $this->assertTrue(is_string($value));
    }

    public function testArray()
    {
        $quote = new Quote(['101', 'lorem ipsum',  1.01]);
        $value = (string) $quote;
        $this->assertEquals("('101', 'lorem ipsum', '1.01')", $value);
        $this->assertTrue(is_string($value));
    }
}