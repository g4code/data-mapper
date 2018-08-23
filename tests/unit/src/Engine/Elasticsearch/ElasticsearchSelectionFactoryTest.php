<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchSelectionFactory;
use G4\DataMapper\Common\SingleValue;

class ElasticsearchSelectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticsearchSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;

    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new ElasticsearchSelectionFactory($this->identityMock);
    }

    protected function tearDown()
    {
        $this->identityMock = null;

        $this->selectionFactory = null;
    }

    public function testFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(['first_name', 'last_name', 'age']);

        $this->assertEquals(['first_name', 'last_name', 'age'], $this->selectionFactory->fieldNames());
    }

    public function testEmptyFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn([]);

        $this->assertEquals([], $this->selectionFactory->fieldNames());
    }

    public function testGroup()
    {
        $this->identityMock
            ->expects($this->any())
            ->method('getGrouping')
            ->willReturn('name');

        $this->assertEquals(['group_by_name' => [ 'terms' => ['field' => 'name']]], $this->selectionFactory->group());
    }

    public function testLimit()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getLimit')
            ->willReturn(5);

        $this->assertEquals(5, $this->selectionFactory->limit());
    }

    public function testOffset()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getOffset')
            ->willReturn(8);

        $this->assertEquals(8, $this->selectionFactory->offset());
    }

    public function testSort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([
                $this->getMockForSort('id', 'desc'),
                $this->getMockForSort('name', 'asc'),
            ]);

        $this->assertEquals([
            ['id'   => ['order' => 'desc']],
            ['name' => ['order' => 'asc']],
        ], $this->selectionFactory->sort());
    }

    public function testSortWithGeodistParameters()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([
                $this->getMockForSort('id', 'desc'),
                $this->getMockForSort('name', 'asc'),
            ]);

        $this->identityMock
            ->expects($this->once())
            ->method('hasCoordinates')
            ->willReturn(true);

        $this->identityMock
            ->expects($this->any())
            ->method('getCoordinates')
            ->willReturn(new \G4\DataMapper\Common\CoordinatesValue(10, 10, 100));

        $this->assertEquals([[
            '_geo_distance' => [
                'location' => [
                    'lat' => 10,
                    'lon' => 10,
                ],
                'order' => 'asc',
                'unit'  => 'km',
                'distance_type' => 'plane',
            ]],
            ['id'   => ['order' => 'desc']],
            ['name' => ['order' => 'asc']],
        ], $this->selectionFactory->sort());
    }

    public function testSortWithEmptyGeodistParameters()
    {

        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([
                $this->getMockForSort('id', 'desc'),
                $this->getMockForSort('name', 'asc'),
            ]);

        $this->identityMock
            ->expects($this->any())
            ->method('getCoordinates')
            ->willReturn(new \G4\DataMapper\Common\CoordinatesValue(10, 10, null));

        $this->assertEquals([
            ['id'   => ['order' => 'desc']],
            ['name' => ['order' => 'asc']],
        ], $this->selectionFactory->sort());

    }

    public function testEmptySort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([]);

        $this->assertEquals([], $this->selectionFactory->sort());
    }

    public function testWhere()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(false);

        $this->identityMock
            ->expects($this->once())
            ->method('getComparisons')
            ->willReturn([
                $this->getMockForEqualComparison('id', 1),
                $this->getMockForEqualComparison('name', 'Test'),
                $this->getMockForGtComparison('age', 18),
            ]);

        $this->assertEquals(
            ['bool' =>
                [
                    'must' =>
                        [
                            ['match' => ['id' => 1]],
                            ['match' => ['name' => 'Test']],
                            ['range' => ['age' => ['gt' => 18]]]
                        ],
                    'filter' => [],
                ],
            ], $this->selectionFactory->where());
    }

    public function testWhereWithRawQuery()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(false);

        $this->identityMock
            ->expects($this->once())
            ->method('getComparisons')
            ->willReturn([
                $this->getMockForEqualComparison('id', 1),
                $this->getMockForEqualComparison('name', 'Test'),
                $this->getMockForGtComparison('age', 18),
            ]);

        $this->identityMock
            ->expects($this->once())
            ->method('hasRawQuery')
            ->willReturn(true);

        $this->identityMock
            ->expects($this->once())
            ->method('getRawQuery')
            ->willReturn(['match' => ['last_name' => 'User']]);

        $this->assertEquals(['bool' =>
            [
                'must' =>
                    [
                        ['match' => ['id' => 1]],
                        ['match' => ['name' => 'Test']],
                        ['range' => ['age' => ['gt' => 18]]],
                        ['match' => ['last_name' => 'User']],
                    ],
                'filter' => [],
            ],
        ], $this->selectionFactory->where());
    }

    public function testWhereIfIdentityIsVoid()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(true);

        $this->assertEquals(['bool' => ['must' => ['match_all' => []]]], $this->selectionFactory->where());
    }

    public function testGetGeodistParameters()
    {
        $this->identityMock
            ->expects($this->any())
            ->method('getCoordinates')
            ->willReturn(new \G4\DataMapper\Common\CoordinatesValue(10, 15, 100));

        $this->identityMock
            ->expects($this->once())
            ->method('hasCoordinates')
            ->willReturn(true);

        $this->identityMock
            ->expects($this->once())
            ->method('getComparisons')
            ->willReturn([]);

        $expectedArray = [
            'bool' => [
                'must'   => [
                    'match_all' => []
                ],
                'filter' => [
                    'geo_distance' => [
                        'distance'     => '100km',
                        'location' => [
                            'lon' => '10',
                            'lat' => '15',
                        ],
                    ]
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $this->selectionFactory->where());
    }

    public function testWhereWithRandom()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(false);

        $this->identityMock
            ->expects($this->once())
            ->method('getComparisons')
            ->willReturn([
                $this->getMockForEqualComparison('id', 1),
                $this->getMockForEqualComparison('name', 'Test'),
                $this->getMockForGtComparison('age', 18),
            ]);

        $this->identityMock
            ->expects($this->once())
            ->method('hasConsistentRandomKey')
            ->willReturn(true);

        $this->identityMock
            ->expects($this->once())
            ->method('getConsistentRandomKey')
            ->willReturn('something');

        $this->assertEquals(
            [
                'function_score' => [
                    'query' => ['bool' =>
                        [
                            'must' =>
                                [
                                    ['match' => ['id' => 1]],
                                    ['match' => ['name' => 'Test']],
                                    ['range' => ['age' => ['gt' => 18]]]
                                ],
                            'filter' => [],
                        ],
                    ],
                    'random_score' => [
                        'seed' => 'something'
                    ]
                ]
            ]

            , $this->selectionFactory->where());
    }

    private function getMockForEqualComparison($column, $value)
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Comparison::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getComparison')
            ->willReturn(['match' => [$column => $value]]);

        $mock
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(new SingleValue($value));

        return $mock;
    }

    private function getMockForGtComparison($column, $value)
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Comparison::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getComparison')
            ->willReturn(['range' => [$column => ['gt' => $value]]]);

        $mock
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(new SingleValue($value));

        return $mock;
    }

    private function getMockForSort($column, $sortDirection)
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Sort::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSort'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getSort')
            ->willReturn([$column => ['order' => $sortDirection]]);

        return $mock;
    }
}
