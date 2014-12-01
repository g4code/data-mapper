<?php

namespace G4\DataMapper\Adapter\Solr;

class Curl
{

    private $url;

    private $response;

    private $requestParams;

    private $query;


    public function __construct($url)
    {
        $this->url = $url;
    }

    public function connect()
    {
        $ch  = curl_init($this->buildUrl());

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['q' => $this->query]);
        curl_setopt($ch, CURLOPT_URL, $this->buildUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $this->response = curl_exec($ch);

        curl_close($ch);
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setRequestParams(array $requestParams)
    {
        $this->requestParams = $requestParams;
        return $this;
    }

    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    private function buildUrl()
    {
        return $this->url . '?' . http_build_query($this->requestParams);
    }
}