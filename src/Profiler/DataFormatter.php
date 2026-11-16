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
        $info = $this->info ?? [];

        return [
            'elapsed_time'     => $this->getFormattedTime($this->timer->getElapsed()),
            'query'            => urldecode($info['url'] ?? ''),
            'redirect_count'   => $info['redirect_count'] ?? null,
            'curl_total_time'  => $this->getFormattedTime($info['total_time'] ?? 0),
            'namelookup_time'  => $this->getFormattedTime($info['namelookup_time'] ?? 0),
            'connect_time'     => $this->getFormattedTime($info['connect_time'] ?? 0),
            'pretransfer_time' => $this->getFormattedTime($info['pretransfer_time'] ?? 0),
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
