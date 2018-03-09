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

    public function testGetCoordinatesWithEmptyParams()
    {
        $this->assertEquals([], $this->solrIdentity->getCoordinates());
    }

    public function testGetCoordinatesWithEmptyGeodistAttributes()
    {
        $this->assertEquals([], $this->solrIdentity->geodist(null, 5, 100)->getCoordinates());
        $this->assertEquals([], $this->solrIdentity->geodist(null, null, 100)->getCoordinates());
        $this->assertEquals([], $this->solrIdentity->geodist(46, null, null)->getCoordinates());
    }

    public function testGetCoordinatesWithParams()
    {
        $this->solrIdentity->geodist(46.100376, 19.667587, 100);

        $expectedArray = [
            'fq'     => '{!geofilt}',
            'sfield' => 'location',
            'pt'     => '46.100376,19.667587',
            'd'      => '100',
        ];

        $this->assertEquals($expectedArray, $this->solrIdentity->getCoordinates());
    }

    public function testGetRawQuery()
    {
        $this->solrIdentity->setRawQuery('some raw query');

        $this->assertEquals('some raw query', $this->solrIdentity->getRawQuery());
    }
}
