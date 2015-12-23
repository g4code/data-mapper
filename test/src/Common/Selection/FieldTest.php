<?php

use G4\DataMapper\Common\Selection\Field;

class FieldTest extends PHPUnit_Framework_TestCase
{

    private $field;

    protected function setUp()
    {
        $this->field = new Field('id');
    }

    protected function tearDown()
    {
        $this->field = null;
    }

    public function testAdd()
    {
        $operatorStub = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Operator')
            ->disableOriginalConstructor()
            ->getMock();

        $this->field->add($operatorStub, 123);

        $this->assertFalse($this->field->isIncomplete());
        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Comparison', $this->field->getComparisons()[0]);
    }

    public function testIsIncomplete()
    {
        $this->assertTrue($this->field->isIncomplete());
    }
}