<?php

namespace G4\DataMapper\Db;

class Db
{
    public static function getProfilerConstInsert()
    {
        return \Zend_Db_Profiler::INSERT;
    }

    public static function isTableRowInstance($obj)
    {
        return $obj instanceof \Zend_Db_Table_Row;
    }

    public static function isExprInstance($obj)
    {
        return $obj instanceof \Zend_Db_Expr;
    }
}
