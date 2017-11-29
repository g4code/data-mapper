<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\SelectionFactoryInterface;

class SolrSelectionFactory implements SelectionFactoryInterface
{
    /**
     * @var IdentityInterface
     */
    private $identity;

    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function fieldNames(){}

    public function group(){}

    public function limit(){}

    public function offset(){}

    public function sort(){}

    public function where(){}
}