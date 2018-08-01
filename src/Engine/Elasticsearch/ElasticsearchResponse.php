<?php


namespace G4\DataMapper\Engine\Elasticsearch;


class ElasticsearchResponse
{

    const KEY_HITS  = 'hits';
    const KEY_TOTAL = 'total';

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
    }

    /**
     * @return array
     */
    public function getHits()
    {
        $decodedResponse = $this->getDecodedResponse();

        return array_key_exists(self::KEY_HITS, $decodedResponse)
            ? $decodedResponse[self::KEY_HITS]
            : [];
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        $decodedResponse = $this->getDecodedResponse();

        return array_key_exists(self::KEY_HITS, $decodedResponse)
            && array_key_exists(self::KEY_TOTAL, $decodedResponse[self::KEY_HITS])
            ? $decodedResponse[self::KEY_HITS][self::KEY_TOTAL]
            : 0;
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
}