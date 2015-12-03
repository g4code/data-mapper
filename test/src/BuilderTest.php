<?php

use G4\DataMapper\Builder;

class BuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \G4\DataMapper\Builder
     */
    private $builder;


    protected function setUp()
    {
        $this->builder = Builder::create();
    }

    protected function tearDown()
    {
        $this->builder = null;
    }

    public function testCreate()
    {
        $this->assertInstanceOf('\G4\DataMapper\Builder', $this->builder);
    }

    public function testBuild()
    {
        $this->builder
            ->type('profiles')
            ->adapter($this->getMock('\G4\DataMapper\Engine\MySQL\MySQLAdapter', null, [[]]));
        $this->builder->build();
    }

    public function testBuildWithNoAdapter()
    {
        $this->builder->type('profiles');
        $this->setExpectedException('\Exception', 'Adapter instance must implement AdapterInterface');
        $this->builder->build();
    }

    public function testBuildWithNoType()
    {
        $this->builder->adapter($this->getMock('\G4\DataMapper\Common\AdapterInterface'));
        $this->setExpectedException('\Exception', 'Type must be set');
        $this->builder->build();
    }

    public function testBuildForUnknownEngine()
    {
        $this->builder
            ->adapter($this->getMock('\G4\DataMapper\Common\AdapterInterface'))
            ->type('profiles');
        $this->setExpectedException('\Exception', 'Unknown engine');
        $this->builder->build();
    }
}