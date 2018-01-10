<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\ValueObject\Url;

class ElasticsearchClient
{
    const DOCUMENT      = 'doc';
    const TIMEOUT       = 5;
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_GET    = 'GET';
    const METHOD_DELETE = 'DELETE';

    private $index;

    /**
     * @var Url
     */
    private $url;

    private $query;

    private $method;


    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    public function insert()
    {
        $this->url = $this->url->path($this->index, self::DOCUMENT);

        $this->executeCurlRequest();
    }

    public function update()
    {
        $this->executeCurlRequest();
    }

    public function get()
    {
        $this->executeCurlRequest();
    }

    public function delete()
    {
        $this->executeCurlRequest();
    }

    public function setQuery($value)
    {
        $this->query = $value;
        return $this;
    }

    public function setIndex($value)
    {
        $this->index = $value;
        return $this;
    }

    public function setMethod($value)
    {
        $this->method = $value;
        return $this;
    }

    public function execute()
    {
        $this->url = $this->url->path($this->index, self::DOCUMENT);

        $this->executeCurlRequest();
    }

    private function executeCurlRequest()
    {
        $handle = curl_init((string) $this->url);

        curl_setopt_array($handle, [
            CURLOPT_POSTFIELDS     => json_encode($this->query),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_URL            => (string) $this->url,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST  => $this->method,
        ]);

        $this->response = curl_exec($handle);

        curl_close($handle);

        return $this;
    }
}
