<?php


use G4\DataMapper\Engine\Http\HttpComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;

class HttpComparisonFormatterTest extends PHPUnit_Framework_TestCase
{


    public function testFormat()
    {
        $httpComparisonFormatter = new HttpComparisonFormatter();

        $operatorStub = $this->getMockBuilder(Operator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $valueMock = $this->getMockBuilder(SingleValue::class)
            ->disableOriginalConstructor()
            ->getMock();

        $valueMock->expects($this->once())->method('__toString')->willReturn('cfd54');

        $this->assertEquals(
            'id=cfd54',
            $httpComparisonFormatter->format('id', $operatorStub, $valueMock)
        );
    }
}
