<?php

namespace G4\DataMapper\Engine\Solr;

use G4\ValueObject\Dictionary;
use G4\ValueObject\Url;

class SolrClient
{
    const SERVICE_NAME  = 'solr';
    const METHOD_SELECT = 'select';
    const METHOD_UPDATE = 'update';
    const TIMEOUT       = 5;

    private $url;

    private $method = self::METHOD_SELECT;

    private $collection = '';

    private $document;

    private $query;

    private $response;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    public function select()
    {
        $this->url = $this->url->path($this->collection, self::SERVICE_NAME, $this->method)->query(new Dictionary($this->query));

        $this->execute()->getResponse();
    }

    public function update()
    {
        $this->method = self::METHOD_UPDATE;

        $this->url = $this->url->path($this->collection, self::SERVICE_NAME, $this->method);

        $this->execute()->getResponse();
    }

    public function getResponse()
    {
        return is_array($this->response) ? $this->response : json_decode($this->response, true);
    }

    public function setCollection($value)
    {
        $this->collection = $value;

        return $this;
    }

    public function setDocument($value)
    {
        $this->document = $value;

        return $this;
    }

    public function setQuery(array $values)
    {
        $this->query = $values;

        return $this;
    }

    private function execute()
    {
        $handle = curl_init($this->url);

        curl_setopt_array($handle, [
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $this->getPostfields(),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_URL            => $this->url,
        ]);

        if ($this->method === self::METHOD_UPDATE) {
            curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $this->response = curl_exec($handle);

        curl_close($handle);

        return $this;
    }

    private function getPostfields()
    {
        return $this->method === self::METHOD_SELECT ? ['q' => $this->query] : json_encode($this->document);
    }
}
