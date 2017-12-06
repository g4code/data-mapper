<?php

namespace G4\DataMapper\Domain;

use G4\DataMapper\Mapper\MapperInterface;
use G4\DataMapper\Mapper\Save;

abstract class ConstsAbstract implements ConstsInterface
{
    public static function getConst($constName)
    {
        $map = static::getMap();
        return static::isValid($constName)
            ? $map[$constName]
            : null;
    }

    public static function getConsts(array $constsNames)
    {
        return array_values(
            array_intersect_key(
                static::getMap(),
                array_flip($constsNames)
            )
        );
    }

    public static function getDefaults()
    {
        return array_flip(static::getMap());
    }

    public static function getName($const)
    {
        return array_search($const, static::getMap());
    }

    public static function getValid()
    {
        return array_flip(static::getMap());
    }

    public static function isValid($constName)
    {
        $map = static::getMap();
        return isset($map[$constName]);
    }
}
