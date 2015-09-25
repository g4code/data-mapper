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
                'bool' => [
                    'must' => $compstrings,
                ],
            ],
        ];
    }
}