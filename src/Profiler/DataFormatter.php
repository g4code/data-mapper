<?php

namespace G4\DataMapper\Profiler;

class DataFormatter
{

    /**
     * @var Timer
     */
    private $timer;

    private $info;

    private $uniqid;


    public function getFormatted()
    {
        return [
            'elapsed_time'     => $this->getFormattedTime($this->timer->getElapsed()),
            'query'            => urldecode($this->info['url']),
            'redirect_count'   => $this->info['redirect_count'],
            'curl_total_time'  => $this->getFormattedTime($this->info['total_time']),
            'namelookup_time'  => $this->getFormattedTime($this->info['namelookup_time']),
            'connect_time'     => $this->getFormattedTime($this->info['connect_time']),
            'pretransfer_time' => $this->getFormattedTime($this->info['pretransfer_time']),
        ];
    }

    public function getTimer()
    {
        return $this->timer;
    }

    public function getUniqId()
    {
        if ($this->uniqid === null) {
            $this->uniqid = uniqid(null, true);
        }
        return $this->uniqid;
    }

    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    public function setTimer($timer)
    {
        $this->timer = $timer;
        return $this;
    }

    public function getFormattedTime($microtime)
    {
        return sprintf("%3f ms", $microtime * 1000);
    }
}
