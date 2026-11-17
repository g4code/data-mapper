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
        $info = $this->info ?? [];

        return parent::getFormatted()
        + [
            'query'            => urldecode($info['url'] ?? ''),
            'redirect_count'   => $info['redirect_count'] ?? null,
            'curl_total_time'  => $this->getFormattedTime($info['total_time'] ?? 0),
            'namelookup_time'  => $this->getFormattedTime($info['namelookup_time'] ?? 0),
            'connect_time'     => $this->getFormattedTime($info['connect_time'] ?? 0),
            'pretransfer_time' => $this->getFormattedTime($info['pretransfer_time'] ?? 0),
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
