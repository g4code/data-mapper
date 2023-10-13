<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Profiler\Ticker\ProfilerTickerElasticsearch;
use G4\ValueObject\Dictionary;
use G4\ValueObject\Url;
use G4\DataMapper\Exception\ClientException;

class ElasticsearchClient
{
    const BULK              = '_bulk';
    const BULK_METHOD       = 'bulk';
    const DOCUMENT          = 'doc';
    const SEARCH            = '_search';
    const TIMEOUT           = 5;
    const METHOD_GET        = 'GET';
    const INSERT            = '_doc';
    const INSERT_METHOD     = 'insert';
    const UPDATE            = '_update';
    const UPDATE_METHOD     = 'update';
    const REFRESH           = '_refresh';
    const RETRY_ON_CONFLICT = 'retry_on_conflict';
    const RETRY_COUNT       = 5;
    const MULTI_SEARCH       = '_msearch';
    const COUNT             = '_count';
    const DEFAULT_ES_VERSION = 2;

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

    private $timeout;

    private $version;

    public function __construct(Url $url, $indexType = null, $timeout = null, $version = null)
    {
        $this->url = $url;
        $this->indexType = $indexType ?: self::DOCUMENT;
        $this->profiler = ProfilerTickerElasticsearch::getInstance();
        $this->timeout = $timeout ?: self::TIMEOUT;
        $this->version = $version ?: self::DEFAULT_ES_VERSION;
    }

    public function execute()
    {
        $this->url = $this->url->path($this->index, $this->indexType, $this->id);

        $this->executeCurlRequest();
    }

    public function executeBulk()
    {
        $this->url = ElasticsearchUrlPathBuilder::generateUrl(
            $this->url,
            $this->index,
            $this->indexType,
            $this->id,
            self::BULK_METHOD,
            $this->version
        );

        $this->executeBulkCurlRequest();
    }

    public function search()
    {
        $this->url = $this->url->path($this->index, self::SEARCH);

        $this->method = self::METHOD_GET;

        $this->executeCurlRequest();

        return $this;
    }

    public function multiSearch()
    {
        $this->url = $this->url->path($this->index, self::MULTI_SEARCH);

        $this->method = self::METHOD_GET;

        $this->submitCurlRequest();

        return $this;
    }

    public function count()
    {
        $this->url = $this->url->path($this->index, self::COUNT);

        $this->method = self::METHOD_GET;

        $this->executeCurlRequest();

        return $this;
    }
    public function insert()
    {
        $this->url = ElasticsearchUrlPathBuilder::generateUrl(
            $this->url,
            $this->index,
            $this->indexType,
            $this->id,
            self::INSERT_METHOD,
            $this->version
        );

        $this->executeCurlRequest();
    }

    public function update()
    {
        $this->url = ElasticsearchUrlPathBuilder::generateUrl(
            $this->url,
            $this->index,
            $this->indexType,
            $this->id,
            self::UPDATE_METHOD,
            $this->version
        )->query(new Dictionary([self::RETRY_ON_CONFLICT => self::RETRY_COUNT]));

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

    public function getIndex()
    {
        return $this->index;
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
        if (!empty($this->body)) {
            $this->body = json_encode($this->body);
        }
        return $this;
    }

    private function prepareBulkBodyData()
    {
        $compiledBody = '';
        foreach ($this->body as $line) {
            //TODO: if line === null, json_encode will convert it to string 'null'.
            // ES7 has a problem with that for methods that don't expect body (example: DELETE).
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
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_URL            => (string) $this->url,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST  => $this->method,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response =  curl_exec($handle);

        $this->responseFactory($response);

        $error = curl_error($handle);

        if ($error) {
            $errorCode = "Curl error number - ". curl_errno($handle);
            $this->throwCurlException($errorCode, $error, $this->url, $this->body, null);
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
            $this->throwCurlException($info['http_code'], $info, $this->url, $this->body, $response);
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

    private function throwCurlException($code, $message, $url, $body, $response)
    {
        throw new ClientException(
            sprintf(
                "Unexpected response code:%s from ES has been returned on submit. More info: %s. Url: %s. Body: %s. Response: %s",
                $code,
                json_encode($message),
                (string) $url,
                json_encode($body),
                is_array($response) ? json_encode($response) : $response
            )
        );
    }
}
