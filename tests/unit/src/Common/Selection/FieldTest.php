<?php

use G4\DataMapper\Common\Selection\Field;

class FieldTest extends \PHPUnit\Framework\TestCase
{

    private $field;

    protected function setUp(): void
    {
        $this->field = new Field('id');
    }

    protected function tearDown(): void
    {
        $this->field = null;
    }

    public function testAdd()
    {
        $operatorStub = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Operator')
            ->disableOriginalConstructor()
            ->getMock();

        $this->field->add($operatorStub, new \G4\DataMapper\Common\SingleValue(123));

        $this->assertFalse($this->field->isIncomplete());
        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Comparison', $this->field->getComparisons()[0]);
    }

    public function testIsIncomplete()
    {
        $this->assertTrue($this->field->isIncomplete());
    }
}
