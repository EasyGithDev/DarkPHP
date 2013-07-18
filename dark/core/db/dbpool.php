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

    public static function add(DbConnector $connector) {

	$name = $connector->getName();

	if (!isset(self::$instances[$name]))
	    self::$instances[$name] = new Db($connector);
    }

    public static function get($name = '') {

	if (!count(self::$instances))
	    return FALSE;

	if (empty($name)) {
	    return array_shift(array_values(self::$instances));
	}

	return isset(self::$instances[$name]) ? self::$instances[$name] : FALSE;
    }

}