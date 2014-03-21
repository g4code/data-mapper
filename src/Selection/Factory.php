<?php

namespace G4\DataMapper\Selection;

use Gee\Log\Writer;

use G4\DataMapper\Selection\Identity;

class Factory
{
    protected $_defaultLimit = 20;

    public function where( Identity $identity = null)
    {
        if ( $identity->isVoid() ) {
            return '1';
        }

        $compstrings = array();

        foreach ( $identity->getComps() as $comp ) {

            if ($comp['operator'] != 'IN') {

                if(\G4\DataMapper\Db\Db::isExprInstance($comp['value'])) {
                    $compstrings[] = "{$comp['name']} {$comp['operator']} {$comp['value']}";
                } else {
                    $compstrings[] = "{$comp['name']} {$comp['operator']} '{$comp['value']}'";
                }

            } else {
                $compstrings[] = "{$comp['name']} {$comp['operator']} {$comp['value']}";
            }
        }

        $where = implode( " AND ", $compstrings );

        return $where;
    }

    public function orderBy( Identity $identity = null )
    {
        $orderByArr = $identity->getOrderBy();

        if ( is_null( $identity ) || empty( $orderByArr ) ) {
            return array();
        }

        $result = array();

        foreach ($orderByArr as $key => $value ) {
            $result[] = $key . ( strtolower( $value ) == 'desc' ? ' DESC' : ' ASC' );
        }

        return $result;
    }

    /**
     *
     * @param Identity $identity
     * @return number
     */
    public function limit(Identity $identity = null )
    {
        if (is_null( $identity)) {
            return $this->_defaultLimit;
        }

        $limit = intval($identity->getLimit());

        return $limit > 0
            ? $limit
            : $this->_defaultLimit;
    }

    /**
     *
     * @param Identity $identity
     * @return string|Ambigous <string, unknown>
     */
    public function offset(Identity $identity = null )
    {
        if (is_null( $identity)) {
            return 0;
        }

        // first page is actually offset zero
        $page = abs(intval($identity->getPage())) - 1;

        $offset = $this->limit($identity) * $page;

        return $offset > 0
            ? $offset
            : 0;
    }
}