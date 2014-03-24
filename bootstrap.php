<?php

header("Content-type: text/html; charset=UTF-8");

// Langage par défaut
ini_set('mbstring.language', 'UTF-8');

// Jeu de caractère interne
ini_set('mbstring.internal_encoding', 'UTF-8');

// Jeu de caractères par défaut pour les données d'entrée HTTP
ini_set('mbstring.http_input', 'UTF-8');

// Jeu de caractères par défaut pour les données de sortie HTTP
ini_set('mbstring.http_output', 'UTF-8');

// Ordre de détection des jeux de caractères
ini_set('mbstring.detect_order', 'auto');

// A faire dans le php.ini ou httpd.conf
//ini_set('mbstring.func_overload', 6);

setlocale(LC_ALL, 'fr_FR');

/**
 * Define constantes
 */
define('DS', DIRECTORY_SEPARATOR);
define('CORE_PATH', __DIR__ . DS . 'dark' . DS . 'core');
define('CONFIGPATH', __DIR__ . DS . 'config');
define('LOGPATH', __DIR__ . DS . 'log');

/**
 * Loading the autoloader
 */
require CORE_PATH . DS . 'autoloader.php';

\Dark\Core\Autoloader::add_namespace('Dark\\Core', CORE_PATH . DS);
\Dark\Core\Autoloader::add_core_namespace('Dark\\Core', true);

$classes = array(
    'Dark\\Core\\Db\\DbConnector' => CORE_PATH . '/db/dbconnector.php',
    'Dark\\Core\\Db\\DbIterator' => CORE_PATH . '/db/dbiterator.php',
    'Dark\\Core\\Db\\Db' => CORE_PATH . '/db/db.php',
    'Dark\\Core\\Error\\Handler' => CORE_PATH . '/error/handler.php',
    'Dark\\Core\\Error\\TxtObserver' => CORE_PATH . '/error/txtobserver.php',
    'Dark\\Core\\Error\\FrontObserver' => CORE_PATH . '/error/frontobserver.php',
    'Dark\\Core\\Profiler' => CORE_PATH . '/error/profiler.php',
    'Dark\\Core\\Config' => CORE_PATH . '/error/config.php',
);

//\Dark\Core\Autoloader::add_classes($classes);
\Dark\Core\Autoloader::register();

foreach (array_keys($classes) as $class)
    Dark\Core\Autoloader::alias_to_namespace($class);

/**
 * Loading the configuration files
 * 
 */
$config = Config::create()
	->load(CONFIGPATH . DS . 'database.ini')
	->load(CONFIGPATH . DS . 'conf.ini');

/**
 * Loading the profiling system
 */
$profiler = Profiler::create();

/**
 * Loading the handler error
 */
$handler = Handler::create()
	->attach(new TxtObserver(LOGPATH))
	->attach(new FrontObserver());

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