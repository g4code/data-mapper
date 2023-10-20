<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchGeodistSortFormatter;

class ElasticsearchGeodistSortFormatterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ElasticsearchIdentity
     */
    private $identity;

    protected function setUp(): void
    {
        $this->identity = new ElasticsearchIdentity();
    }

    public function testSortWithGeodistParameters()
    {
        $this->identity->geodist(5, 10, 100);

        $geodistFormatter = new ElasticsearchGeodistSortFormatter($this->identity);

        $expectedArray = [
            '_geo_distance' => [
                'location' => [
                    'lat' => '5',
                    'lon' => '10',
                ],
                'order' => 'asc',
                'unit'  => 'km',
                'distance_type' => 'plane',
            ],
        ];

        $this->assertEquals($expectedArray, $geodistFormatter->format('_geo_distance', 'ASC'));
    }

    public function testSortWithEmptyGeodistParameters()
    {
        $geodistFormatter = new ElasticsearchGeodistSortFormatter($this->identity);

        $this->assertEquals([], $geodistFormatter->format('_geo_distance', 'ASC'));
    }
}
