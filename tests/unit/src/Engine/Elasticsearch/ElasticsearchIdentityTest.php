<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity;

class ElasticsearchIdentityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticsearchIdentity
     */
    private $elasticsearchIdentity;

    protected function setUp()
    {
        $this->elasticsearchIdentity = new ElasticsearchIdentity();
    }

    public function testGeodist()
    {
        $this->assertInstanceOf(ElasticsearchIdentity::class, $this->elasticsearchIdentity->field('location')->geodist(10, 15));
    }

    public function testHasCoordinates()
    {
        $this->assertEquals(false, $this->elasticsearchIdentity->geodist(null, 5, 100)->hasCoordinates());
        $this->assertEquals(false, $this->elasticsearchIdentity->geodist(null, null, 100)->hasCoordinates());
        $this->assertEquals(false, $this->elasticsearchIdentity->geodist(46, null, null)->hasCoordinates());

        $this->assertEquals(false, $this->elasticsearchIdentity->hasCoordinates());
    }

    public function testGetRawQuery()
    {
        $this->elasticsearchIdentity->setRawQuery('test query');

        $this->assertEquals('test query', $this->elasticsearchIdentity->getRawQuery());
    }

    public function testHasRawQuery()
    {
        $this->elasticsearchIdentity->setRawQuery('test query');

        $this->assertEquals(true, $this->elasticsearchIdentity->hasRawQuery());
    }

    public function testGetConsistentRandomKey()
    {
        $this->elasticsearchIdentity->setConsistentRandomKey('something');

        $this->assertEquals('something', $this->elasticsearchIdentity->getConsistentRandomKey());
    }

    public function testHasConsistentRandomKey()
    {
        $this->assertEquals(false, $this->elasticsearchIdentity->hasConsistentRandomKey());

        $this->elasticsearchIdentity->setConsistentRandomKey('something');

        $this->assertEquals(true, $this->elasticsearchIdentity->hasConsistentRandomKey());
    }

    public function testTimeFromInMinutes()
    {
        $this->assertInstanceOf(ElasticsearchIdentity::class, $this->elasticsearchIdentity->field('online')->timeFromInMinutes(15));
    }

    public function testLikeCI()
    {
        $identity = new ElasticsearchIdentity();
        $this->assertInstanceOf(ElasticsearchIdentity::class, $identity->field('id')->likeCI('this'));

        $this->setExpectedException('\Exception', 'Value can not be array');
        $identity->field('name')->likeCI([1]);
    }
}
