<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\Identity;

class SolrIdentity extends Identity implements SolrIdentityInterface
{
    private $rawQuery;

    public function setRawQuery($value)
    {
        $this->rawQuery = $value;
    }

    public function getRawQuery()
    {
        $this->rawQuery;
    }

    public function hasRawQuery()
    {
        return !empty($this->rawQuery);
    }
}
