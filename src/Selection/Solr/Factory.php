<?php

namespace G4\DataMapper\Selection\Solr;

use G4\DataMapper\Selection\IdentityInterface;

class Factory extends \G4\DataMapper\Selection\Factory
{

    public function fieldList(IdentityInterface $identity = null)
    {
        if (!$identity->hasFieldList()) {
            return \G4\DataMapper\Selection\Solr\Consts\Query::WILDCARD;
        }
        $fieldList = $identity->getFieldList();
        foreach ($identity->getFieldList() as $key => $oneField) {
            if (is_array($oneField)) {
                $fieldList[$key] = key($oneField) . \G4\DataMapper\Selection\Solr\Consts\Query::COLON . current($oneField);
            }
        }
        return empty($fieldList)
            ? \G4\DataMapper\Selection\Solr\Consts\Query::WILDCARD
            : join(",", $fieldList);
    }

    public function orderBy(IdentityInterface $identity = null)
    {
        if (is_null($identity) || !$identity->hasOrderBy()) {
            return '';
        }
        $sort = [];
        foreach ($identity->getOrderBy() as $key => $value) {
            if ($key !== null) {
                $sort[] = $key . ' ' . (strtolower($value) == 'desc' ? \G4\DataMapper\Selection\Solr\Consts\Query::DESCENDING : \G4\DataMapper\Selection\Solr\Consts\Query::ASCENDING);
            }
        }
        return empty($sort) ? '' : join(",", $sort);
    }

    public function requestParams(IdentityInterface $identity = null)
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
                'd'      => $identity->hasGeoDistance() ? $identity->getGeodist()['distance'] : \G4\DataMapper\Selection\Solr\Consts\Query::MAX_DISTANCE,
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

    public function query(IdentityInterface $identity = null)
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
        return empty($compstrings)
            ? $this->queryAll()
            : join(' ' . \G4\DataMapper\Selection\Solr\Consts\Query::CONNECTOR_AND . ' ', $compstrings);
    }

    private function between($value)
    {
        return \G4\DataMapper\Selection\Solr\Consts\Query::BRACKET_OPEN
            . join(' ' . \G4\DataMapper\Selection\Solr\Consts\Query::TO .  ' ', $value)
            . \G4\DataMapper\Selection\Solr\Consts\Query::BRACKET_CLOSE;
    }

    private function queryAll()
    {
        return \G4\DataMapper\Selection\Solr\Consts\Query::WILDCARD . \G4\DataMapper\Selection\Solr\Consts\Query::COLON . \G4\DataMapper\Selection\Solr\Consts\Query::WILDCARD;
    }
}