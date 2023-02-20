<?php

namespace G4\DataMapper\Profiler;

/** @deprecated */
class Search
{

    private $totalElapsedTime;

    private $data;

    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->totalElapsedTime = 0;
        }
        return self::$instance;
    }

    public function getTotalElapsedTime()
    {
        return $this->totalElapsedTime;
    }

    public function getTotalNumQueries()
    {
        return count($this->data);
    }


    public function end($uniqueId)
    {
        $this->data[$uniqueId]->getTimer()->end();
        $this->totalElapsedTime += $this->data[$uniqueId]->getTimer()->getElapsed();
    }

    public function getData()
    {
        return $this->getTotalNumQueries() > 0
            ? $this->data
            : [];
    }

    public function setInfo($uniqueId, array $info)
    {
        $this->data[$uniqueId]->setInfo($info);
    }

    public function start()
    {
        $dataFormatter = new DataFormatter();
        $dataFormatter
            ->setTimer((new Timer())->start());
        $this->data[$dataFormatter->getUniqId()] = $dataFormatter;
        return $dataFormatter->getUniqId();
    }
}
