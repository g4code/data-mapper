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
        $info = $this->info ?? [];

        return parent::getFormatted()
            + [
                'url'              => urldecode($info['url'] ?? ''),
                'method'           => $this->method,
                'query'            => $this->query,
                'redirect_count'   => $info['redirect_count'] ?? null,
                'curl_total_time'  => $this->getFormattedTime($info['total_time'] ?? 0),
                'namelookup_time'  => $this->getFormattedTime($info['namelookup_time'] ?? 0),
                'connect_time'     => $this->getFormattedTime($info['connect_time'] ?? 0),
                'pretransfer_time' => $this->getFormattedTime($info['pretransfer_time'] ?? 0),
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
