<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Profiler\Ticker\ProfilerTickerElasticsearch;
use G4\ValueObject\Url;
use G4\DataMapper\Exception\ClientException;

class ElasticsearchClient
{
    const BULK          = '_bulk';
    const DOCUMENT      = 'doc';
    const SEARCH        = '_search';
    const TIMEOUT       = 5;
    const METHOD_GET    = 'GET';
    const UPDATE        = '_update';
    const REFRESH       = '_refresh';

    private $index;

    /**
     * @var string
     */
    private $indexType;

    /**
     * @var Url
     */
    private $url;

    private $body;

    private $method;

    private $id;

    /**
     * @var ElasticsearchResponse
     */
    private $response;
    /**
     * @var ProfilerTickerElasticsearch
     */
    private $profiler;

    public function __construct(Url $url, $indexType = null)
    {
        $this->url = $url;
        $this->indexType = $indexType ?: self::DOCUMENT;
        $this->profiler = ProfilerTickerElasticsearch::getInstance();
    }

    public function execute()
    {
        $this->url = $this->url->path($this->index, $this->indexType, $this->id);

        $this->executeCurlRequest();
    }

    public function executeBulk()
    {
        $this->url = $this->url->path($this->index, $this->indexType, self::BULK);

        $this->executeBulkCurlRequest();
    }

    public function search()
    {
        $this->url = $this->url->path($this->index, self::SEARCH);

        $this->method = self::METHOD_GET;

        $this->executeCurlRequest();

        return $this;
    }

    public function update()
    {
        $this->url = $this->url->path($this->index, $this->indexType, $this->id, self::UPDATE);

        $this->executeCurlRequest();
    }
    
    public function refresh()
    {
        $this->url = $this->url->path($this->index, self::REFRESH);

        $this->executeCurlRequest();
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

    public function setBody($value)
    {
        $this->body = $value;
        return $this;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response->getHits();
    }

    /**
     * @return int
     */
    public function getTotalItemsCount()
    {
        return $this->response->getTotal();
    }

    public function responseFactory($response)
    {
        $this->response = new ElasticsearchResponse($response);
    }

    private function executeCurlRequest()
    {
        $this
            ->prepareBodyData()
            ->submitCurlRequest();

        return $this;
    }

    private function executeBulkCurlRequest()
    {
        $this
            ->prepareBulkBodyData()
            ->submitCurlRequest();

        return $this;
    }

    private function prepareBodyData()
    {
        $this->body = json_encode($this->body);
        return $this;
    }

    private function prepareBulkBodyData()
    {
        $compiledBody = '';
        foreach ($this->body as $line) {
            $compiledBody .= json_encode($line) . "\n";
        }
        $this->body = $compiledBody;
        return $this;
    }

    private function submitCurlRequest()
    {
        $uniqueId = $this->profiler->start();
        $handle = curl_init((string) $this->url);

        curl_setopt_array($handle, [
            CURLOPT_POSTFIELDS     => $this->body,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_URL            => (string) $this->url,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST  => $this->method,
        ]);

        $this->responseFactory(curl_exec($handle));

        $error = curl_error($handle);

        if ($error) {
            throw new ClientException(sprintf("Submit curl request failed. Error: %s",  $error));
        }

        $info = curl_getinfo($handle);

        $this->profiler->setInfo(
            $uniqueId,
            $info,
            $this->method,
            $this->body
        );

        curl_close($handle);
        $this->profiler->end($uniqueId);

        if (isset($info['http_code']) && (int) $info['http_code'] >= 400 && (int) $info['http_code'] <= 599) {
            throw new ClientException(
                sprintf(
                    "Unexpected response code:%s from ES has been returned on submit. More info:%s",
                    $info['http_code'],
                    json_encode($info)
                )
            );
        }

        return $this;
    }

    public function hasError()
    {
        return $this->response->hasError();
    }

    public function getErrorMessage()
    {
        return $this->response->getErrorMessage();
    }

    public function getUrl()
    {
        return $this->url;
    }
}
