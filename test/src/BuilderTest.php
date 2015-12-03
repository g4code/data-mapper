<?php

class BuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \G4\DataMapper\Builder
     */
    private $builder;


    protected function setUp()
    {
        $this->builder = \G4\DataMapper\Builder::create();
    }

    protected function tearDown()
    {
        $this->builder = null;
    }

    public function testCreate()
    {
        $this->assertInstanceOf();
    }
}