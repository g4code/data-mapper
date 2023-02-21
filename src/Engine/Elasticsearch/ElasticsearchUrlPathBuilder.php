<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\ValueObject\Url;

class ElasticsearchUrlPathBuilder
{
    /**
     * @param Url $url
     * @param $index
     * @param $indexType
     * @param $id
     * @param $method
     * @param $version
     * @return Url
     */
    public static function generateUrl(Url $url, $index, $indexType, $id, $method, $version)
    {
        $classPath = ElasticsearchVersionFactory::getVersionedClassPath('ElasticsearchClientUrlPath', $version);

        return $classPath::$method($url, $index, $indexType, $id);
    }
}
