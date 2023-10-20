<?php

namespace unit\src\Engine\Elasticsearch;

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchUrlPathBuilder;
use G4\ValueObject\Url;
use PHPUnit_Framework_TestCase;

class ElasticsearchUrlPathBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testUpdateVersion8()
    {
        $result = ElasticsearchUrlPathBuilder::generateUrl(
            new Url('https://UrlPath'),
            'indexTest',
            'indexTypeTest',
            100,
            'update',
            8
        );
        self::assertEquals(new Url('https://UrlPath/indexTest/_update/100'), $result);
    }

    public function testUpdateVersionLessThen8()
    {
        $versions = [2, 6, 7];
        foreach ($versions as $version) {
            $result = ElasticsearchUrlPathBuilder::generateUrl(
                new Url('https://UrlPath'),
                'indexTest',
                'indexTypeTest',
                100,
                'update',
                $version
            );
            self::assertEquals(new Url('https://UrlPath/indexTest/indexTypeTest/100/_update'), $result);
        }
    }
}
