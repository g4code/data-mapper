<?php

namespace G4\DataMapper\Profiler\Ticker;

use G4\DataMapper\Profiler\Solr\Formatter;
use G4\Profiler\Ticker\TickerAbstract;

//TODO: Drasko - tmp solution - fix after Profiler refactoring is done!
class ProfilerTickerSolr extends TickerAbstract
{

    const NAME = 'data_mapper_solr_1.x';
    const TYPE = 'solr';

    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return ProfilerTickerSolr
     */
    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }


    /**
     * @return Formatter
     */
    public function getDataFormatterInstance()
    {
        return new Formatter();
    }

    public function getName()
    {
        return self::NAME;
    }
    
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @param string $uniqueId
     * @param array $info
     */
    public function setInfo($uniqueId, array $info)
    {
        $this->getDataPart($uniqueId)->setInfo($info);
    }
}
