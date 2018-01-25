<?php

use G4\DataMapper\Engine\Solr\SolrIdentity;

class SolrIdentityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SolrIdentity
     */
    private $solrIdentity;

    protected function setUp()
    {
        $this->solrIdentity = new SolrIdentity();
    }

    protected function tearDown()
    {
        $this->solrIdentity = null;
    }

    public function testTimeFromInMinutes()
    {
        $this->assertInstanceOf(SolrIdentity::class, $this->solrIdentity->field('online')->timeFromInMinutes(15));
    }

    public function testGeodist()
    {
        $this->assertInstanceOf(SolrIdentity::class, $this->solrIdentity->field('location')->geodist(10, 15));
    }

    public function testHasRawQueryTrueValue()
    {
        $this->solrIdentity->setRawQuery('gender:M');

        $this->assertEquals(true, $this->solrIdentity->hasRawQuery());
    }

    public function testHasRawQueryFalseValue()
    {
        $this->assertEquals(false, $this->solrIdentity->hasRawQuery());
    }
}
