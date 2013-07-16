<?php

/**
 * Define constantes
 */
define('DS', DIRECTORY_SEPARATOR);
define('COREPATH', __DIR__ . DS . 'dark' . DS . 'core');
define('CONFIGPATH', __DIR__ . DS . 'config');
define('LOGPATH', __DIR__ . DS . 'log');

/**
 * Loading the autoloader
 */
require COREPATH . DS . 'autoloader.php';

\Dark\Core\Autoloader::add_namespace('Dark\\Core', COREPATH . DS);
\Dark\Core\Autoloader::add_core_namespace('Dark\\Core', true);

$classes = array(
    'Dark\\Core\\Db\\DbConnector' => COREPATH . '/db/dbconnector.php',
    'Dark\\Core\\Db\\DbIterator' => COREPATH . '/db/dbiterator.php',
    'Dark\\Core\\Db\\Db' => COREPATH . '/db/db.php',
    'Dark\\Core\\Error\\Handler' => COREPATH . '/error/handler.php',
    'Dark\\Core\\Error\\TxtObserver' => COREPATH . '/error/txtobserver.php',
    'Dark\\Core\\Profiler' => COREPATH . '/error/profiler.php',
    'Dark\\Core\\Config' => COREPATH . '/error/config.php',
);

//\Dark\Core\Autoloader::add_classes($classes);
\Dark\Core\Autoloader::register();

foreach (array_keys($classes) as $class)
    Dark\Core\Autoloader::alias_to_namespace($class);


/**
 * Do we have access to mbstring?
 * We need this in order to work with UTF-8 strings
 */
define('MBSTRING', function_exists('mb_get_info'));


/**
 * Loading the configuration files
 * 
 */
$config = Config::getInstance()
	->load(CONFIGPATH . DS . 'database.ini')
	->load(CONFIGPATH . DS . 'conf.ini');

/**
 * Loading the profiling system
 */
$profiler = Profiler::getInstance();

/**
 * Loading the handler error
 */
$handler = new Handler();
$handler->attach(new TxtObserver(LOGPATH));

/**
 * Register all the error/shutdown handlers
 */
register_shutdown_function(function () use($profiler) {
	    echo nl2br($profiler);
	});

set_exception_handler(function (\Exception $e) use($handler) {
	    $handler->trace($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
	});

set_error_handler(function ($severity, $message, $filepath, $line) use($handler) {
	    $handler->trace($severity, $message, $filepath, $line);
	});