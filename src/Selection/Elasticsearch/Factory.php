<?php

namespace G4\DataMapper\Selection\Elasticsearch;

class Factory extends \G4\DataMapper\Selection\Factory
{

    public function query(\G4\DataMapper\Selection\Elasticsearch\Identity $identity = null)
    {
        $compstrings = [];
        foreach ($identity->getComps() as $comp) {
            if ($comp['value'] !== null) {
                $compstrings[][$comp['operator']][$comp['name']] = $comp['value'];
            }
        }
        return [
            'query' => [
                'filtered' => [
                    'query' => [
                        'match_all' => [],
                    ],
                    'filter' => [
                        'bool' => [
                            'must' => [
                                ['term' => ['country_id' => 201],],
                                ['term' => ['city_id' => 14359],],
                                ['term' => ['site_id' => 1],],
//                                 ['term' => ['city_name' => 'Madrid'],],
//                                 ['term' => ['gender' => 'F'],],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}