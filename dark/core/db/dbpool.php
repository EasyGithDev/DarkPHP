<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Db;

/**
 * Description of dbservice
 *
 * @author florent
 */
class DbPool {

    private static $instances;

    public static function add(\Dark\Core\Db\DbConnection $connection) {

	$name = $connection->getName();

	if (!isset(self::$instances[$name]))
	    self::$instances[$name] = new \Dark\Core\Db\Db($connection);
    }

    public static function get($name = '') {

	if (!count(self::$instances))
	    return FALSE;

	if (empty($name))
	    return self::$instances[0];

	return isset(self::$instances[$name]) ? self::$instances[$name] : FALSE;
    }

}