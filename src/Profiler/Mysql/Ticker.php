<?php

namespace G4\DataMapper\Profiler\Mysql;

use G4\DI\Container as DI;

class Ticker extends \G4\Profiler\Ticker\TickerAbstract
{

    private $db;

    public function __construct(\G4\DataMapper\Adapter\Mysql\Db $dbAdapter) {
        $this->db = $dbAdapter->get();
    }

    private function __clone() {}

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_mapper_mysql';
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
        if ($this->getDbProfiler()->getTotalNumQueries()) {
            foreach ($this->getDbProfiler()->getQueryProfiles() as $queryProfile) {
                $queries[] = [
                    'elapsed_time' => $this->getDataFormatterInstance()->getFormattedTime($queryProfile->getElapsedSecs()),
                    'query'        => $queryProfile->getQuery()
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