<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Db\Db;
use G4\DataMapper\Selection\IdentityInterface;
use Rhumsaa\Uuid\Console\Exception;

class Factory
{
    protected $_defaultLimit = 20;

    private $db;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    /**
     * @param Identity $identity
     * @return string
     */
    public function where(IdentityInterface $identity = null)
    {
        if ($identity->isVoid()) {
            return '1';
        }

        if($this->db === null){
            throw new \Exception("Missing Mysql db adapter", 500);
        }

        $compstrings = array();

        foreach ($identity->getComps() as $comp) {
            $s = sprintf("%s %s ", $comp['name'], $comp['operator']);

            $s .= ($comp['operator'] != 'IN' && !\G4\DataMapper\Db\Db::isExprInstance($comp['value']))
                ? sprintf("%s", $this->db->quote($comp['value']))
                : $comp['value'];

            $compstrings[] = $s;
        }

        $where = implode(" AND ", $compstrings);

        return $where;
    }

    /**
     * @param Identity $identity
     * @return string
     */
    public function orderBy(IdentityInterface $identity = null)
    {
        $orderByArr = $identity->getOrderBy();

        if (is_null($identity) || empty($orderByArr)) {
            return array();
        }

        $result = array();

        foreach ($orderByArr as $key => $value) {
            $result[] = $key . ' ' . (strtolower($value) == 'desc' ? 'DESC' : 'ASC');
        }

        return $result;
    }

    /**
     *
     * @param Identity $identity
     * @return int
     */
    public function limit(IdentityInterface $identity = null)
    {
        if (is_null($identity)) {
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
     * @return int
     */
    public function offset(IdentityInterface $identity = null)
    {
        if (is_null($identity)) {
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