<?php

namespace G4\DataMapper\Selection\Elasticsearch;

class Factory extends \G4\DataMapper\Selection\Factory
{

    public function query(\G4\DataMapper\Selection\Elasticsearch\Identity $identity = null)
    {
        $compstrings = [];
        foreach ($identity->getComps() as $comp) {
            if ($comp['value'] !== null) {
                $compstrings['match'][$comp['name']] = $comp['value'];
            }
        }
        return [
            'query' => $compstrings,
        ];
    }
}