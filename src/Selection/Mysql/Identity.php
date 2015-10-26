<?php

namespace G4\DataMapper\Selection\Mysql;

use G4\DataMapper\Selection\IdentityField;
use G4\DataMapper\Selection\IdentityAbstract;

class Identity extends IdentityAbstract
{


    /**
     * @return Identity
     */
    public function eq($value = null)
    {
        $this->operator("=", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function neq($value = null)
    {
        $this->operator("<>", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function gt($value = null)
    {
        $this->operator(">", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function in($fields = array())
    {
        $fields = "('" . implode("', '", $fields) . "')";

        $this->operator('IN', $fields);
        return $this;
    }

    /**
     * @return Identity
     */
    public function like($value = null)
    {
        $this->operator("LIKE", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardLeft($value = null)
    {
        $this->operator("LIKE", "%{$value}");
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardRight($value = null)
    {
        $this->operator("LIKE", "{$value}%");
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardBoth($value = null)
    {
        $this->operator("LIKE", "%{$value}%");
        return $this;
    }

    /**
     * @return Identity
     */
    public function lt($value = null)
    {
        $this->operator("<", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function le($value = null)
    {
        $this->operator("<=", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function ge($value = null)
    {
        $this->operator(">=", $value);
        return $this;
    }
}