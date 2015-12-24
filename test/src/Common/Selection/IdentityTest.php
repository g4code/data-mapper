<?php

use G4\DataMapper\Common\Selection\Identity;

class IdentityTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Identity
     */
    private $identity;


    protected function setUp()
    {
        $this->identity = new Identity();
    }

    protected function tearDown()
    {
        $this->identity = null;
    }

    public function testEqual()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->equal(1));

        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Comparison', $this->identity->getComparisons()[0]);

        $this->setExpectedException('\Exception', 'Value cannot be array');
        $this->identity->field('name')->equal([1]);
    }

    public function testGreaterThan()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->greaterThan(1));
    }

    public function testGreaterThanOrEqual()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->greaterThanOrEqual(1));
    }

    public function testIn()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->in([1]));
    }

    public function testLessThan()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->lessThan([1]));
    }

    public function testLessThanOrEqual()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->lessThanOrEqual(1));
    }

    public function testNotEqual()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->notEqual(1));
    }

    public function testNotIn()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->notIn([1]));
    }

    public function testField()
    {
        $this->assertTrue($this->identity->isVoid());

        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('name'))  ;

        $this->assertFalse($this->identity->isVoid());

        $this->setExpectedException('\Exception', 'Incomplete field');
        $this->identity->field('name');
    }

    public function testFieldAlreadySet()
    {
        $this->identity
            ->field('id')
            ->equal(1);

        $this->setExpectedException('\Exception', 'Field is already set');
        $this->identity->field('id');
    }

    public function testFieldIsNotDefined()
    {
        $this->setExpectedException('\Exception', 'Field is not defined');
        $this->identity->equal(1);
    }

    public function testGetComparisons()
    {
        $this->identity
            ->field('id')->equal(123)
            ->field('username')->equal('me');
        $comparisons = $this->identity->getComparisons();

        $this->assertTrue(is_array($comparisons));
        $this->assertEquals(2, count($comparisons));
        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Comparison', $comparisons[0]);
    }
}