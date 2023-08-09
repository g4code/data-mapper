<?php

namespace G4\DataMapper\Profiler\Mysql;

class Ticker extends \G4\Profiler\Ticker\TickerAbstract
{

    const NAME = 'data_mapper_mysql_0.x';
    const TYPE = 'db';

    /**
     * @var \G4\DataMapper\Adapter\Mysql\Db
     */
    private $db;

    public function __construct(\G4\DataMapper\Adapter\Mysql\Db $dbAdapter)
    {
        $this->db = $dbAdapter->get();
    }

    private function __clone()
    {
    }

    public function clear()
    {
        $this->getDbProfiler()->clear();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return int
     */
    public function getTotalElapsedTime()
    {
        return $this->getDbProfiler()->getTotalElapsedSecs();
    }

    /**
     * @return int
     */
    public function getTotalNumQueries()
    {
        return $this->getDbProfiler()->getTotalNumQueries();
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        if ($this->getDbProfiler()->getTotalNumQueries() && $this->getDbProfiler()->getQueryProfiles()) {
            foreach ($this->getDbProfiler()->getQueryProfiles() as $queryProfile) {
                $queries[(string) $queryProfile->getStartedMicrotime()] = [
                    'elapsed_time' =>
                        $this->getDataFormatterInstance()->getFormattedTime($queryProfile->getElapsedSecs()),
                    'query' => $queryProfile->getQuery()
                ];
            }
        }
        return isset($queries) ? $queries : [];
    }

    /**
     * @return \Zend_Db_Profiler
     */
    private function getDbProfiler()
    {
        return  $this->db->getProfiler();
    }
}
