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

namespace Dark\Core;

class Application {

    protected static $instance;
    protected $configPath = '';
    protected $applicationPath = '';
    protected $configFiles = array();
    protected $encoding = 'UTF-8';
    protected $local = 'fr_FR';
    protected $errorHandler = NULL;
    protected $exceptionHandler = NULL;
    protected $router = NULL;
    protected $profiler = NULL;

    protected function __construct() {
	
    }

    public static function create() {
	if (is_null(self::$instance)) {
	    self::$instance = new self();
	}
	return self::$instance;
    }

    public function setConfigPath($configPath) {
	$this->configPath = $configPath;
	return $this;
    }

    public function setApplicationPath($applicationPath) {
	$this->applicationPath = $applicationPath;
	return $this;
    }

    public function getApplicationPath() {
	return $this->applicationPath;
    }

    public function setEncoding($encoding) {
	$this->encoding = $encoding;
	return $this;
    }

    public function setErrorHandler() {
	return $this;
    }

    public function setExceptionHandler() {
	return $this;
    }

    public function setLocal($local) {
	$this->local = $local;
    }

    public function setRouter($router) {
	$this->router = $router;
	return $this;
    }

    public function setProfiler($profiler) {
	$this->profiler = $profiler;
	return $this;
    }

    public function setConfigFiles($configFiles) {
	$this->configFiles = $configFiles;
	return $this;
    }

    public function start() {

	header('Content-type: text/html; charset=' . $this->encoding);

	// Langage par défaut
	ini_set('mbstring.language', $this->encoding);

	// Jeu de caractère interne
	ini_set('mbstring.internal_encoding', $this->encoding);

	// Jeu de caractères par défaut pour les données d'entrée HTTP
	ini_set('mbstring.http_input', $this->encoding);

	// Jeu de caractères par défaut pour les données de sortie HTTP
	ini_set('mbstring.http_output', $this->encoding);

	// Ordre de détection des jeux de caractères
	ini_set('mbstring.detect_order', 'auto');

	// A faire dans le php.ini ou httpd.conf
	//ini_set('mbstring.func_overload', 6);

	setlocale(LC_ALL, $this->local);

	$registry = Registry\Registry::create();

	// Loading the configuration files
	foreach ($this->configFiles as $v) {
	    if (($config = Config::load($this->configPath . DIRECTORY_SEPARATOR . $v)) !== FALSE) {
		$key = pathinfo($v, PATHINFO_FILENAME);
		$registry->$key = $config;
	    }
	}

	// Loading the profiling system
	$this->profiler = Profiler::create();

	// Loading the router
	$this->router = new Router($this);
	$this->router->run();
    }

}