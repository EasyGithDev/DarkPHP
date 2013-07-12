<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core;

/**
 * Description of autoloader
 *
 * @author florent
 */
class Autoloader {

    /**
     * Register's the autoloader to the SPL autoload stack.
     *
     * @return	void
     */
    public static function register() {
	spl_autoload_register('Dark\\Core\\Autoloader::load', true, true);
    }

    /**
     * Aliases the given class into the given Namespace.  By default it will
     * add it to the global namespace.
     *
     * <code>
     * Autoloader::alias_to_namespace('Foo\\Bar');
     * Autoloader::alias_to_namespace('Foo\\Bar', '\\Baz');
     * </code>
     *
     * @param  string  $class      the class name
     * @param  string  $namespace  the namespace to alias to
     */
    public static function alias_to_namespace($class, $namespace = '') {
	empty($namespace) or $namespace = rtrim($namespace, '\\') . '\\';
	$parts = explode('\\', $class);
	$root_class = $namespace . array_pop($parts);
	class_alias($class, $root_class);
    }

    /**
     * Loads a class.
     *
     * @param   string  $class  Class to load
     * @return  bool    If it loaded the class
     */
    public static function load($className) {

	$className = ltrim($className, '\\');
	$fileName = '';
	$namespace = '';

	if ($lastNsPos = strripos($className, '\\')) {
	    $namespace = substr($className, 0, $lastNsPos);
	    $className = substr($className, $lastNsPos + 1);
	    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}

	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	require strtolower($fileName);
    }

}