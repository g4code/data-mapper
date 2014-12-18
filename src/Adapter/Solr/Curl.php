<?php

namespace G4\DataMapper\Adapter\Solr;

class Curl
{

    const TIMEOUT       = 5;
    const METHOD_SELECT = 'select';
    const METHOD_UPDATE = 'update';

    /**
     * @var array
     */
    private $document;

    /**
     * @var string
     */
    private $method;

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
     * @var string
     */
    private $url;

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
        return is_array($this->response)
            ? $this->response
            : json_decode($this->response, true);
    }

    /**
     * @return array
     */
    public function select()
    {
        $this->method = self::METHOD_SELECT;
        return $this
            ->exec()
            ->getResponse();
    }

    public function setDocument($document)
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return \G4\DataMapper\Adapter\Solr\Curl
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
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

    public function update()
    {
        $this->method = self::METHOD_UPDATE;
        return $this
            ->exec()
            ->getResponse();
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
                '/',
                $this->method,
                $this->httpBuildQuery(),
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
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $this->getPostfields(),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => $this->getTimeout(),
            CURLOPT_URL            => $this->buildUrl(),
        ]);
        if ($this->method === self::METHOD_UPDATE) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
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

    private function getPostfields()
    {
        return $this->method === self::METHOD_SELECT
            ? ['q' => $this->query]
            : json_encode($this->document);
    }

    private function getTimeout()
    {
        return empty($this->params['timeout'])
            ? self::TIMEOUT
            : $this->params['timeout'];
    }

    private function httpBuildQuery()
    {
        return $this->method === self::METHOD_SELECT
            ? '/?' . http_build_query($this->requestParams)
            : '';
    }
}