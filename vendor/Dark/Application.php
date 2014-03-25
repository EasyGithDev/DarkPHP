<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * DarkPHP - a PHP library of components
 *
 * @author      Florent Brusciano
 * @copyright   2013 Florent Brusciano
 * @version     1.0.0
 * @package     Dark\Core
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Dark;

class Application {

    protected static $instance;
    protected $data = array(
	'configPath' => '',
	'applicationPath' => '',
	'configFiles' => array(),
	'encoding' => 'UTF-8',
	'local' => 'fr_FR',
	'useErrorHandler' => true,
	'errorPlugins' => array(),
	'exceptionHandler' => NULL,
	'router' => NULL,
	'useProfiler' => true,
	'env' => '',
    );

    protected function __construct() {
	
    }

    public function __destruct() {
	if ($this->data['useProfiler'])
	    echo Profiler::create();
    }

    public static function create() {
	if (is_null(self::$instance)) {
	    self::$instance = new self();
	}
	return self::$instance;
    }

    public function __set($name, $value) {
	$this->data[$name] = $value;
    }

    public function __get($name) {
	return isset($this->data[$name]) ? $this->data[$name] : FALSE;
    }

    public function start() {

	// Create the registry
	$registry = Registry::create();
/*
	// Loading the configuration files
	foreach ($this->data['configFiles'] as $v) {
	    if (($config = Config::load($this->data['configPath'] . DIRECTORY_SEPARATOR . $v)) !== FALSE) {
		$key = pathinfo($v, PATHINFO_FILENAME);
		$registry->$key = $config;
	    }
	}
*/
	header('Content-type: text/html; charset=' . $this->data['encoding']);

	// Langage par défaut
	ini_set('mbstring.language', $this->data['encoding']);

	// Jeu de caractère interne
	ini_set('mbstring.internal_encoding', $this->data['encoding']);

	// Jeu de caractères par défaut pour les données d'entrée HTTP
	ini_set('mbstring.http_input', $this->data['encoding']);

	// Jeu de caractères par défaut pour les données de sortie HTTP
	ini_set('mbstring.http_output', $this->data['encoding']);

	// Ordre de détection des jeux de caractères
	ini_set('mbstring.detect_order', 'auto');

	// A faire dans le php.ini ou httpd.conf
	//ini_set('mbstring.func_overload', 6);

	setlocale(LC_ALL, $this->data['local']);

	// Loading the profiling system
	if ($this->data['useProfiler'])
	    Profiler::create();

	// Loading the error handler
	if ($this->data['useErrorHandler']) {
	    $errorHandler = Handler::create()->register();
	    foreach ($this->data['errorHandlerPlugins'] as $plugin) {
		$errorHandler->attach($plugin);
	    }
	}

	// Loading the router
	$this->data['router'] = new Router($this);
	$this->data['router']->run();
    }

}
