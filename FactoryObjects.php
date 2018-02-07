<?php

namespace zvsv\Logger;

//require 'LogFileObject.php';

/**
 * The factory makes objects of log files and return them
 *
 * Class FactoryObjects
 * @package zvsv\Logger
 */

class FactoryObjects
{
    protected static $instance;

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * It's create new object
     *
     * @param string $name
     * @return LogFileObject
     */
    public function getObject(string $name) {
        return new LogFileObject($name);
    }
}