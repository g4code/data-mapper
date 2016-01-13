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

        $this->setExpectedException('\Exception', 'Value cannot be array');
        $this->identity->field('name')->greaterThan([1]);
    }

    public function testGreaterThanOrEqual()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->greaterThanOrEqual(1));

        $this->setExpectedException('\Exception', 'Value cannot be array');
        $this->identity->field('name')->greaterThanOrEqual([1]);
    }

    public function testIn()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->in([1]));
    }

    public function testLessThan()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->lessThan(1));

        $this->setExpectedException('\Exception', 'Value cannot be array');
        $this->identity->field('name')->lessThan([1]);
    }

    public function testLessThanOrEqual()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->lessThanOrEqual(1));

        $this->setExpectedException('\Exception', 'Value cannot be array');
        $this->identity->field('name')->lessThanOrEqual([1]);
    }

    public function testLike()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->like('this'));

        $this->setExpectedException('\Exception', 'Value cannot be array');
        $this->identity->field('name')->like([1]);
    }

    public function testLimit()
    {
        $this->assertEquals(20, $this->identity->getLimit());

        $this->identity->setLimit(9);
        $this->assertEquals(9, $this->identity->getLimit());

        $this->identity->setPerPage(15);
        $this->assertEquals(15, $this->identity->getLimit());
    }

    public function testNotEqual()
    {
        $this->assertInstanceOf('G4\DataMapper\Common\Selection\Identity', $this->identity->field('id')->notEqual(1));

        $this->setExpectedException('\Exception', 'Value cannot be array');
        $this->identity->field('name')->notEqual([1]);
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

    public function testSorting()
    {
        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Identity', $this->identity->sortAscending('name'));
        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Identity', $this->identity->sortDescending('ts'));

        $sorting = $this->identity->getSorting();

        $this->assertEquals(2, count($sorting));
        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Sort', $sorting['name']);
        $this->assertInstanceOf('\G4\DataMapper\Common\Selection\Sort', $sorting['ts']);
    }

    public function testGrouping()
    {
        $this->assertSame($this->identity, $this->identity->groupBy('name'));
        $this->identity->groupBy('ts');
        $this->assertEquals(['name', 'ts'], $this->identity->getGrouping());
    }

    public function testOffset()
    {
        $this->assertEquals(0, $this->identity->getOffset());

        $this->identity->setPage(2);
        $this->assertEquals(20, $this->identity->getOffset());

        $this->identity->setOffset(3);
        $this->assertEquals(3, $this->identity->getOffset());
    }
}