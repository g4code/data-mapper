<?php

namespace G4\DataMapper\Selection\Mysql;

use G4\DataMapper\Selection\IdentityField;
use G4\DataMapper\Selection\IdentityAbstract;

class Identity extends IdentityAbstract
{

    const WILDCARD_POSITION_BOTH  = 'WILDCARD_POSITION_BOTH';
    const WILDCARD_POSITION_LEFT  = 'WILDCARD_POSITION_LEFT';
    const WILDCARD_POSITION_RIGHT = 'WILDCARD_POSITION_RIGHT';


    /**
     * @param string $value
     * @return Identity
     */
    public function equal($value)
    {
        $this->operator("=", $value);
        return $this;
    }
    /**
     * OBSOLETE !!! USE equal($value)
     */
    public function eq($value = null)
    {
        return $this->equal($value);
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function greaterThan($value)
    {
        $this->operator(">", $value);
        return $this;
    }
    /**
     * OBSOLETE !!! USE greaterThan($value)
     */
    public function gt($value = null)
    {
        return $this->greaterThan($value);
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function greaterThanOrEqual($value)
    {
        $this->operator(">=", $value);
        return $this;
    }
    /**
     * OBSOLETE !!! USE greaterThanOrEqual($value)
     */
    public function ge($value = null)
    {
        return $this->greaterThanOrEqual($value);
    }

    /**
     * @param array $fields
     * @return Identity
     */
    public function in(array $values = null)
    {
        $values = empty($values) ? null : "('" . implode("', '", $values) . "')";
        $this->operator('IN', $values);
        return $this;
    }

    /**
     * @param string $value
     * @param string $wildCardPosition
     * @return Identity
     */
    public function like($value, $wildCardPosition = null)
    {
        if ($wildCardPosition === self::WILDCARD_POSITION_BOTH) {
            $value = "%{$value}%";
        }
        if ($wildCardPosition === self::WILDCARD_POSITION_LEFT) {
            $value = "%{$value}";
        }
        if ($wildCardPosition === self::WILDCARD_POSITION_RIGHT) {
            $value = "{$value}%";
        }
        $this->operator("LIKE", $value);
        return $this;
    }
    /**
     * OBSOLETE !!! USE like($value, $wildCardPosition = null)
     */
    public function likeWildcardBoth($value = null)
    {
        return $this->like($value, self::WILDCARD_POSITION_BOTH);
    }
    /**
     * OBSOLETE !!! USE like($value, $wildCardPosition = null)
     */
    public function likeWildcardLeft($value = null)
    {
        return $this->like($value, self::WILDCARD_POSITION_LEFT);
    }
    /**
     * OBSOLETE !!! USE like($value, $wildCardPosition = null)
     */
    public function likeWildcardRight($value = null)
    {
        return $this->like($value, self::WILDCARD_POSITION_RIGHT);
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function lessThan($value)
    {
        $this->operator("<", $value);
        return $this;
    }
    /**
     * OBSOLETE !!! USE lessThan($value)
     */
    public function lt($value = null)
    {
        return $this->lessThan($value);
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function lessThanOrEqual($value)
    {
        $this->operator("<=", $value);
        return $this;
    }
    /**
     * OBSOLETE !!! USE lessThanOrEqual($value)
     */
    public function le($value = null)
    {
        return $this->lessThanOrEqual($value);
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function notEqual($value)
    {
        $this->operator("<>", $value);
        return $this;
    }
    /**
     * OBSOLETE !!! USE notEqual($value)
     */
    public function neq($value = null)
    {
        return $this->notEqual($value);
    }

    /**
     * @param array $fields
     * @return Identity
     */
    public function notIn(array $fields)
    {
        $fields = empty($fields) ? null : "('" . implode("', '", $fields) . "')";
        $this->operator('NOT IN', $fields);
        return $this;
    }
    /**
     * OBSOLETE !!! USE notIn(array $fields)
     */
    public function nin($fields = array())
    {
        return $this->notIn($fields);
    }
}