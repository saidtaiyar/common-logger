<?php

namespace zvsv\commonLogger;

/**
 * This class is a pool of the objects. You can get some object by hash.
 *
 * Class Pool
 * @package zvsv\Logger
 */

class Pool
{
    /**
     * Array of objects which use in Logger
     *
     * @var Object
     */
    protected static $objects = [];

    /**
     * Function for getting some object by its hash
     *
     * @param string $hash
     * @return Object
     */
    public static function get(string $name): Object {
        $hash = md5($name);
        if(!isset(self::$objects[$hash])){
            self::$objects[$hash] = FactoryObjects::getInstance()->getObject($name);
        }

        return self::$objects[$hash];
    }

    /**
     * If you need to get all object in the pool, you can call the function
     *
     * @return array
     */
    public static function getAll() : array {
        return self::$objects;
    }
}