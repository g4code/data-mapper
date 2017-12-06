<?php

namespace G4\DataMapper\Profiler;

class Timer
{

    /**
     * @var float
     */
    private $ended;


    /**
     * @var float
     */
    private $started;


    public function end()
    {
        $this->ended = microtime(true);
        return $this;
    }

    public function getElapsed()
    {
        return $this->ended - $this->started;
    }

    public function start()
    {
        $this->started = microtime(true);
        return $this;
    }
}
