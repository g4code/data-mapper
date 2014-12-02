<?php

namespace G4\DataMapper\Adapter\Solr;

class Curl
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $params;

    /**
     * @var string
     */
    private $response;

    /**
     * @var array
     */
    private $requestParams;

    /**
     * @var string
     */
    private $query;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param array $requestParams
     * @param string $query
     */
    public function select(array $requestParams, $query)
    {
        return $this
            ->setRequestParams($requestParams)
            ->setQuery($query)
            ->exec()
            ->getResponse();
    }

    /**
     * @param array $requestParams
     */
    public function setRequestParams(array $requestParams)
    {
        $this->requestParams = $requestParams;
        return $this;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return string
     */
    private function buildUrl()
    {
        if ($this->url === null) {
            $this->url = join('', [
                $this->getHost(),
                ':',
                $this->getPort(),
                '/solr',
                $this->getCollection(),
                "/select/?",
                http_build_query($this->requestParams)
            ]);
        }
        return $this->url;
    }

    /**
     * @return \G4\DataMapper\Adapter\Solr\Curl
     */
    private function exec()
    {
        $ch  = curl_init($this->buildUrl());
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => ['q' => $this->query],
            CURLOPT_URL            => $this->buildUrl(),
            CURLOPT_RETURNTRANSFER => 1
        ]);
        $this->response = curl_exec($ch);
        curl_close($ch);
        return $this;
    }

    private function getCollection()
    {
        return empty($this->params['collection'])
            ? ''
            : '/' . $this->params['collection'];
    }

    private function getHost()
    {
        if (empty($this->params['host'])) {
            throw new \Exception('Solr host param is missing!');
        }
        return $this->params['host'];
    }

    private function getPort()
    {
        if (empty($this->params['port'])) {
            throw new \Exception('Solr port param is missing!');
        }
        return $this->params['port'];
    }
}