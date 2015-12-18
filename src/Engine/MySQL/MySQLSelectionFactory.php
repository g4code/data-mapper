<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\SelectionIdentityInterface;

class MySQLSelectionFactory implements SelectionFactoryInterface
{

    /**
     * @var SelectionIdentityInterface
     */
    private $identity;

    /**
     * @param SelectionIdentityInterface $identity
     */
    public function __construct(SelectionIdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function fields()
    {

    }

    public function group()
    {

    }

    public function sort()
    {

    }

    public function where()
    {
        if ($this->identity->isVoid()) {
            return '1';
        }

        $compstrings = [];

        foreach ($identity->getComps() as $comp) {
            $s = sprintf("%s %s ", $comp['name'], $comp['operator']);

            $s .= ($comp['operator'] != 'IN')
            ? sprintf("%s", $this->db->quote($comp['value']))
            : $comp['value'];

            $compstrings[] = $s;
        }

        $where = implode(" AND ", $compstrings);

        return $where;
    }

    public function limit()
    {

    }
}