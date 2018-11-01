<?php

namespace G4\DataMapper\Selection\Solr;

use G4\DataMapper\Selection\Solr\Consts\Query;

class Factory extends \G4\DataMapper\Selection\Factory
{

    public function fieldList(\G4\DataMapper\Selection\Solr\Identity $identity = null)
    {
        if (!$identity->hasFieldList()) {
            return Query::WILDCARD;
        }
        $fieldList = $identity->getFieldList();
        foreach ($identity->getFieldList() as $key => $oneField) {
            if (is_array($oneField)) {
                $fieldList[$key] = key($oneField) . Query::COLON . current($oneField);
            }
        }
        return empty($fieldList)
            ? Query::WILDCARD
            : join(",", $fieldList);
    }

    public function orderBy(\G4\DataMapper\Selection\Identity $identity = null)
    {
        if (is_null($identity) || !$identity->hasOrderBy()) {
            return '';
        }
        $sort = [];
        foreach ($identity->getOrderBy() as $key => $value) {
            if ($key !== null) {
                $sort[] = $key . ' ' . (strtolower($value) == 'desc' ? Query::DESCENDING : Query::ASCENDING);
            }
        }
        return empty($sort) ? '' : join(",", $sort);
    }

    public function requestParams(\G4\DataMapper\Selection\Solr\Identity $identity = null)
    {
        $params = [
            'fl'    => $this->fieldList($identity),
            'rows'  => $this->limit($identity),
            'sort'  => $this->orderBy($identity),
            'start' => $this->offset($identity),
            'wt'    => 'json',
        ];
        if ($identity->hasGeoLatitudeAndLongitude()) {
            $params += [
                'd'      => $identity->hasGeoDistance() ? $identity->getGeodist()['distance'] : Query::MAX_DISTANCE,
                'sfield' => $identity->getGeodist()['spatialField'],
                'pt'     => join(',', [$identity->getGeodist()['latitude'], $identity->getGeodist()['longitude']]),
                'fq'     => $identity->getGeodist()['filterQuery']
            ];
        }
        if ($identity->hasGroupBy()) {
            $params += [
                'group'       => 'true',
                'group.field' => $identity->getGroupBy(),
                'group.main'  => 'true',
            ];
        }
        return $params;
    }

    public function query(\G4\DataMapper\Selection\Solr\Identity $identity = null)
    {
        if ($identity->isVoid()) {
            return $this->queryAll();
        }
        $compstrings = [];
        foreach ($identity->getComps() as $comp) {
            if ($comp['value'] !== null) {
                $compstrings[] = $comp['name'] .
                                 $comp['operator'] .
                                 (is_array($comp['value']) ? $this->between($comp['value']) : $comp['value']);
            }
        }

        $query = empty($compstrings)
            ? $this->queryAll()
            : join(' ' . Query::CONNECTOR_AND . ' ', $compstrings);

        return $identity->hasRawQuery()
            ? sprintf('%s AND %s', $identity->getRawQuery(), $query)
            : $query;
    }

    private function between($value)
    {
        return Query::BRACKET_OPEN
            . join(' ' . Query::TO .  ' ', $value)
            . Query::BRACKET_CLOSE;
    }

    private function queryAll()
    {
        return Query::WILDCARD . Query::COLON . Query::WILDCARD;
    }
}
