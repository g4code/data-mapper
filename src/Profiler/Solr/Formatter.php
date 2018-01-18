<?php

namespace G4\DataMapper\Profiler\Solr;

class Formatter extends \G4\Profiler\Ticker\Formatter
{

    /**
     * @var array
     */
    private $info;

    /**
     * @return array
     */
    public function getFormatted()
    {
        return parent::getFormatted()
        + [
            'query'            => urldecode($this->info['url']),
            'redirect_count'   => $this->info['redirect_count'],
            'curl_total_time'  => $this->getFormattedTime($this->info['total_time']),
            'namelookup_time'  => $this->getFormattedTime($this->info['namelookup_time']),
            'connect_time'     => $this->getFormattedTime($this->info['connect_time']),
            'pretransfer_time' => $this->getFormattedTime($this->info['pretransfer_time']),
        ];
    }

    /**
     * @param array $info
     * @return \G4\DataMapper\Profiler\Formatter
     */
    public function setInfo(array $info)
    {
        $this->info = $info;
        return $this;
    }
}
