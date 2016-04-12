<?php

namespace G4\DataMapper\Selection\Solr;

class Identity extends \G4\DataMapper\Selection\Identity
{

    private $fieldList = [];

    private $groupBy;

    private $geodist = [];

    private $rawQuery;

    public function addToFieldList($field)
    {
        $this->fieldList[] = $field;
        return $this;
    }

    public function between($min, $max)
    {
        return $this->_operator(
            \G4\DataMapper\Selection\Solr\Consts\Query::COLON,
            $this->getValue(new \G4\DataMapper\Selection\Solr\IdentityValue\Between($min, $max))
        );
    }

    public function betweenDates($min, $max)
    {
        return $this->_operator(
            \G4\DataMapper\Selection\Solr\Consts\Query::COLON,
            $this->getValue(new \G4\DataMapper\Selection\Solr\IdentityValue\BetweenDates($min, $max))
        );
    }

    public function eq($value = null)
    {
        return $this->_operator(
            \G4\DataMapper\Selection\Solr\Consts\Query::COLON,
            $value
        );
    }

    public function geodist($latitude, $longitude, $distance = null)
    {
        if ($latitude !== null || $longitude !== null ) {
             $this->geodist = [
                'latitude'     => $latitude,
                'longitude'    => $longitude,
                'distance'     => $distance,
                'spatialField' => 'location',
                'filterQuery'  => '{!geofilt}'
            ];
            $this
                ->addToFieldList(['_dist_' => 'geodist()'])
                ->setOrderBy('geodist()', 'asc');
        }
        return $this;
    }

    public function getRawQuery()
    {
        return $this->rawQuery;
    }

    public function getFieldList()
    {
        return $this->fieldList;
    }

    public function getGeodist()
    {
        return $this->geodist;
    }

    public function hasRawQuery()
    {
        return !empty($this->rawQuery);
    }

    public function getGroupBy()
    {
        return $this->groupBy;
    }

    public function hasFieldList()
    {
        return !empty($this->fieldList);
    }

    public function hasGeoDistance()
    {
        return !empty($this->geodist['distance']);
    }

    public function hasGroupBy()
    {
        return !empty($this->groupBy);
    }

    public function hasGeoLatitudeAndLongitude()
    {
        return !empty($this->geodist['latitude']) && !empty($this->geodist['longitude']);
    }

    public function in($value = array())
    {
        if (empty($value)) {
            $value = null;
        }
        return $this->_operator(
            \G4\DataMapper\Selection\Solr\Consts\Query::COLON,
            $this->getValue(new \G4\DataMapper\Selection\Solr\IdentityValue\In($value))
        );
    }

    public function like($value = null)
    {
        return $this->_operator(
            \G4\DataMapper\Selection\Solr\Consts\Query::COLON,
            $this->getValue(new \G4\DataMapper\Selection\Solr\IdentityValue\Like($value))
        );
    }

    public function setRawQuery($rawQuery)
    {
        $this->rawQuery = $rawQuery;
        return $this;
    }

    public function setFieldList(array $fieldList)
    {
        $this->fieldList = $fieldList;
        return $this;
    }

    public function setGroupBy($fieldname)
    {
        $this->groupBy = $fieldname;
        return $this;
    }

    public function setOrderBy($param, $value)
    {
        return $param !== null
            ? parent::setOrderBy($param, $value)
            : $this;
    }

    public function timeFromInMinutes($value)
    {
        return $this->_operatorAttach(
            \G4\DataMapper\Selection\Solr\Consts\Query::COLON,
            $this->getValue(new \G4\DataMapper\Selection\Solr\IdentityValue\TimeRange($value, null, $this->getCurrentField()))
        );
    }

    public function timeToInMinutes($value)
    {
        return $this->_operatorAttach(
            \G4\DataMapper\Selection\Solr\Consts\Query::COLON,
            $this->getValue(new \G4\DataMapper\Selection\Solr\IdentityValue\TimeRange(null, $value, $this->getCurrentField()))
        );
    }

    private function getValue(\G4\DataMapper\Selection\Solr\IdentityValue\IdentityValueInterface $identityValue)
    {
        return $identityValue->value();
    }
}