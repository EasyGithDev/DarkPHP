<?php

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Création du bootstrap';
echo '<br/>-----------------------------------------------------------------<br/>';


/**
 * Define constantes
 */
define('DS', DIRECTORY_SEPARATOR);
define('CORE_PATH', __DIR__ . DS . '../vendor' . DS . 'dark');
define('CONFIG_PATH', __DIR__ . DS . '../config');
define('LOG_PATH', __DIR__ . DS . '../log');
define('APPLICATION_PATH', __DIR__ . DS . '../application');

/**
 * Loading the autoloader
 */
require CORE_PATH . DS . 'Autoloader.php';


$classes = array(
    'Dark\\Core\\Db\\DbConnector' => CORE_PATH . '/db/dbconnector.php',
    'Dark\\Core\\Db\\DbIterator' => CORE_PATH . '/db/dbiterator.php',
    'Dark\\Core\\Db\\Db' => CORE_PATH . '/db/db.php',
    'Dark\\Core\\Error\\Handler' => CORE_PATH . '/error/handler.php',
    'Dark\\Core\\Error\\Csv' => CORE_PATH . '/error/csv.php',
    'Dark\\Core\\Error\\Display' => CORE_PATH . '/error/display.php',
    'Dark\\Core\\Profiler' => CORE_PATH . '/error/profiler.php',
    'Dark\\Core\\Config' => CORE_PATH . '/error/config.php',
);

\Dark\Autoloader::register();

//foreach (array_keys($classes) as $class)
    //Dark\Core\Autoloader::alias_to_namespace($class);

$app = Dark\Application::create();
$app->applicationPath = APPLICATION_PATH;
$app->configPath = CONFIG_PATH;
//->setLogPath(LOG_PATH)
$app->configFiles = array('conf.ini', 'database.ini');
$app->start();