<?php

/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Dark;

/**
 * The Autloader is responsible for all class loading.  It allows you to define
 * different load paths based on namespaces.  It also lets you set explicit paths
 * for classes to be loaded from.
 *
 * @package     Fuel
 * @subpackage  Core
 */
class Autoloader {

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
     * Register's the autoloader to the SPL autoload stack.
     *
     * @return	void
     */
    public static function register() {
	spl_autoload_register('Dark\\Autoloader::autoload', true, true);
    }

    public static function autoload($className) {
	$className = ltrim($className, '\\');
	$fileName = '';
	$namespace = '';
	if ($lastNsPos = strripos($className, '\\')) {
	    $namespace = substr($className, 0, $lastNsPos);
	    $className = substr($className, $lastNsPos + 1);
	    //$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
echo $fileName;
	require $fileName;
    }

}
