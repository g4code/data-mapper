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

    public function fieldNames()
    {
        $fieldNames = $this->identity->getFieldNames();

        return count($fieldNames) === 0
            ? '*'
            : $fieldNames;
    }

    public function group()
    {
        return $this->identity->getGrouping();
    }

    public function limit()
    {
        return (int) $this->identity->getLimit();
    }

    public function offset()
    {
    }

    public function sort()
    {
    }

    public function where()
    {
    }
}
