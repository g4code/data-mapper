<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchGeodistFormatter;

class ElasticsearchGeodistFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticsearchGeodistFormatter
     */
    private $identity;

    protected function setUp()
    {
        $this->identity = new \G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity();
    }

    public function testFormatWithGeodistParameters()
    {
        $this->identity->geodist(10, 5, 100);

        $geodistFormatter = new ElasticsearchGeodistFormatter($this->identity);

        $expectedArray = [
            'geo_distance' => [
                'distance'     => '100km',
                'pin.location' => [
                    'lon' => '10',
                    'lat' => '5',
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $geodistFormatter->format());
    }

    public function testWithEmptyGeodistParameters()
    {
        $geodistFormatter = new ElasticsearchGeodistFormatter($this->identity);

        $this->assertEquals([], $geodistFormatter->format());
    }
}
