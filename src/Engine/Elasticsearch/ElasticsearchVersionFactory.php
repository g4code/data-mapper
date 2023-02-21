<?php

namespace G4\DataMapper\Engine\Elasticsearch;

class ElasticsearchVersionFactory
{
    /**
     * $defaultClassName is class path in current namespace, without namespace part
     * @param $defaultClassName
     * @param $version
     * @return string
     */
    public static function getVersionedClassPath($defaultClassName, $version = ElasticsearchClient::DEFAULT_ES_VERSION)
    {
        while ($version > 0) {
            $versionedPath = __NAMESPACE__ . "\\Version$version\\$defaultClassName";
            if (class_exists($versionedPath)) {
                return $versionedPath;
            }
            $version--;
        }

        return __NAMESPACE__ . "\\$defaultClassName";
    }
}
