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

    /**
     * @var Url
     */
    private $url;

    private $query;


    public function __construct(Url $url, $index)
    {
        $this->url = $url->path($index, self::DOCUMENT);
    }

    public function insert()
    {
        $this->execute(self::METHOD_POST);
    }

    public function update()
    {
        $this->execute(self::METHOD_PUT);
    }

    public function get()
    {
        $this->execute(self::METHOD_GET);
    }

    public function delete()
    {
        $this->execute(self::METHOD_DELETE);
    }

    public function setQuery($value)
    {
        $this->query = $value;
        return $this;
    }

    private function execute($method)
    {
        $handle = curl_init((string) $this->url);

        curl_setopt_array($handle, [
            CURLOPT_POSTFIELDS     => json_encode($this->query),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_URL            => (string) $this->url,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST  => $method,
        ]);

        $this->response = curl_exec($handle);

        curl_close($handle);

        return $this;
    }
}
