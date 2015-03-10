<?php

namespace G4\DataMapper\Db;

use G4\DI\Container as DI;

//TODO: Drasko: change this to work with DI and remove static calls
class Db
{
    public static function getAdapter()
    {
//         return \Zend_Db_Table::getDefaultAdapter();
        return DI::get('db');
    }

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