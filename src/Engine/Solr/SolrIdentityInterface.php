<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\IdentityInterface;

interface SolrIdentityInterface extends IdentityInterface
{
    public function getRawQuery();

    public function setRawQuery($value);

    public function hasRawQuery();
}
