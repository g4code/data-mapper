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

    private $method;

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
        $this->method = self::METHOD_SELECT;

        $this->url = $this->url->path(self::SERVICE_NAME, $this->collection, $this->method)->query(new Dictionary($this->query));

        $this->execute();

        return $this->getResponse();
    }

    public function update()
    {
        $this->method = self::METHOD_UPDATE;

        $this->url = $this->url->path(self::SERVICE_NAME, $this->collection, $this->method);

        $this->execute();

        return $this->getResponse();
    }

    public function getResponse()
    {
        return is_array($this->response) ? $this->response : json_decode($this->response, true);
    }

    public function getDocuments()
    {
        return (empty($this->getResponse()) || $this->getResponse() === null) ? [] : $this->getResponse()['response']['docs'];
    }

    public function getTotalItemsCount()
    {
        $decodedResponse = json_decode($this->response, true);

        return empty($decodedResponse['response']['numFound'])
            ? 0
            : $decodedResponse['response']['numFound'];
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
        $handle = curl_init((string) $this->url);

        curl_setopt_array($handle, [
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $this->getPostfields(),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_URL            => (string) $this->url,
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
        return $this->method === self::METHOD_SELECT ? http_build_query($this->query) : json_encode($this->document);
    }
}
