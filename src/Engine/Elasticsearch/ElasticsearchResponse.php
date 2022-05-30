<?php


namespace G4\DataMapper\Engine\Elasticsearch;

use Elasticsearch\Common\Exceptions\ClientErrorResponseException;
use G4\DataMapper\Domain\TotalCount;

class ElasticsearchResponse
{

    const KEY_HITS  = 'hits';

    const KEY_ERROR      = 'error';
    const KEY_TYPE       = 'type';
    const KEY_ROOT_CAUSE = 'root_cause';
    const KEY_RESPONSES_M_SEARCH = 'responses';

    /**
     * @var array
     */
    private $decodedResponse;

    /**
     * @var string
     */
    private $response;

    /**
     * ElasticsearchResponse constructor.
     * @param $response
     */
    public function __construct($response)
    {
        $this->response = $response;
        $this->getDecodedResponse();
    }

    /**
     * @return array
     */
    public function getHits()
    {
        return array_key_exists(self::KEY_RESPONSES_M_SEARCH, $this->decodedResponse)
            ? $this->getHitFromMultiSearch() : $this->getHitsFromSearch();
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        if ($this->hasError() || !array_key_exists(self::KEY_HITS, $this->decodedResponse)) {
            return 0;
        } else {
            return (new TotalCount($this->decodedResponse[self::KEY_HITS]))->getValue();
        }
    }

    /**
     * @return array
     */
    private function getDecodedResponse()
    {
        if ($this->decodedResponse === null) {
            $this->decodedResponse = json_decode($this->response, true);
        }
        return $this->decodedResponse;
    }

    public function hasError()
    {
        return $this->decodedResponse === null || array_key_exists(self::KEY_ERROR, $this->decodedResponse);
    }

    public function getErrorMessage()
    {
        if ($this->decodedResponse === null) {
            return json_encode(['Error decoding response', $this->response]);
        }
        return json_encode([
            $this->decodedResponse[self::KEY_ERROR][self::KEY_TYPE],
            $this->decodedResponse[self::KEY_ERROR][self::KEY_ROOT_CAUSE],
        ]);
    }

    private function getHitFromMultiSearch()
    {
        $multiSearchHits = [];
        foreach ($this->decodedResponse[self::KEY_RESPONSES_M_SEARCH] as $singleHits) {
            if (array_key_exists(self::KEY_HITS, $singleHits)) {
                $multiSearchHits[] = $singleHits[self::KEY_HITS];
            };
        }

        return $multiSearchHits;
    }

    private function getHitsFromSearch()
    {
        return array_key_exists(self::KEY_HITS, $this->decodedResponse)
            ? $this->decodedResponse[self::KEY_HITS]
            : [];
    }
}
