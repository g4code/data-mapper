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

    public function testCoordinatesSet()
    {
        $this->assertEquals(false, $this->elasticsearchIdentity->geodist(null, 5, 100)->coordinatesSet());
        $this->assertEquals(false, $this->elasticsearchIdentity->geodist(null, null, 100)->coordinatesSet());
        $this->assertEquals(false, $this->elasticsearchIdentity->geodist(46, null, null)->coordinatesSet());

        $this->assertEquals(false, $this->elasticsearchIdentity->coordinatesSet());
    }

    public function testGetCoordinatesWithParams()
    {
        $this->elasticsearchIdentity->geodist(46.100376, 19.667587, 100);

        $expectedArray = [
            'geo_distance' => [
                'distance'     => '100km',
                'pin.location' => [
                    'lon' => 46.100376,
                    'lat' => 19.667587,
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $this->elasticsearchIdentity->getCoordinates()->formatForElasticsearch());
    }
}
