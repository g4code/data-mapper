<?php

namespace G4\DataMapper\Profiler\Elasticsearch;

class Formatter extends \G4\Profiler\Ticker\Formatter
{
    private $query;

    private $info;

    private $method;

    /**
     * @return array
     */
    public function getFormatted()
    {
        return parent::getFormatted()
            + [
                'url'              => urldecode($this->info['url']),
                'method'           => $this->method,
                'query'            => $this->query,
                'redirect_count'   => $this->info['redirect_count'],
                'curl_total_time'  => $this->getFormattedTime($this->info['total_time']),
                'namelookup_time'  => $this->getFormattedTime($this->info['namelookup_time']),
                'connect_time'     => $this->getFormattedTime($this->info['connect_time']),
                'pretransfer_time' => $this->getFormattedTime($this->info['pretransfer_time']),
            ];
    }

    public function setInfo($info)
    {
        $this->info = $info;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }
}
