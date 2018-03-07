<?php

use G4\DataMapper\Common\CoordinatesValue;

class CoordinatesValueTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var CoordinatesValue
     */
    private $coordinates;

    protected function setUp()
    {
        $this->coordinates = new CoordinatesValue(46.100376, 19.667587, 100);
    }

    protected function tearDown()
    {
        $this->coordinates = null;
    }

    public function testFormatForSolr()
    {
        $expectedArray = [
            'fq'     => '{!geofilt}',
            'sfield' => 'location',
            'pt'     => '46.100376,19.667587',
            'd'      => '100',
        ];

        $this->assertEquals($expectedArray, $this->coordinates->formatForSolr());
    }

    public function testIsEmpty()
    {
        $this->assertEquals(true, (new CoordinatesValue(null, null, null))->isEmpty());
        $this->assertEquals(true, (new CoordinatesValue(null, null, 5))->isEmpty());
        $this->assertEquals(true, (new CoordinatesValue(100, null, null))->isEmpty());
    }
}
