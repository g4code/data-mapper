<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchGeodistSort;

class ElasticsearchGeodistSortTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticsearchIdentity
     */
    private $identity;

    protected function setUp()
    {
        $this->identity = new ElasticsearchIdentity();
    }

    public function testSortWithGeodistParameters()
    {
        $this->identity->geodist(5, 10, 100);

        $geodistFormatter = new ElasticsearchGeodistSort($this->identity);

        $expectedArray = [[
            '_geo_distance' => [
                'location' => [
                    'lat' => '5',
                    'lon' => '10',
                ],
                'order' => 'asc',
                'unit'  => 'km',
                'distance_type' => 'plane',
            ],
        ]];

        $this->assertEquals($expectedArray, $geodistFormatter->sort());
    }

    public function testSortWithEmptyGeodistParameters()
    {
        $geodistFormatter = new ElasticsearchGeodistSort($this->identity);

        $this->assertEquals([], $geodistFormatter->sort());
    }
}
