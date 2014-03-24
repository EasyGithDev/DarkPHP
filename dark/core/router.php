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

class Router {

    const INIT_METHOD = 'init';
    const DEFAULT_MODULE = '';
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_METHOD = 'index';
    const URI_DELIMITER = '/';
    const CONTROLLER_EXT = '.php';
    const SUFFIX_METHOD = 'Action';
    const SUFFIX_OBJECT = 'Controller';

    protected static $instance;
    protected $app = NULL;
    protected $controllerPath = '';
    protected $viewPath = '';
    protected $controller = NULL;
    protected $view = NULL;
    protected $routes = array();

    public function __construct(Application $app) {
	$this->app = $app;
	$this->viewPath = $this->app->applicationPath . DIRECTORY_SEPARATOR . 'php/view';
	$this->controllerPath = $this->app->applicationPath . DIRECTORY_SEPARATOR . 'php/controller';
    }

    public function run() {
	$uri = $_SERVER['REQUEST_URI'];

	$route = array();
	foreach ($this->routes as $k => $v) {
	    if (preg_match($k, $uri)) {
		$route = $v;
		break;
	    }
	}

	if (count($route) == 0) {
	    $route['module'] = self::DEFAULT_MODULE;
	    $route['controller'] = self::DEFAULT_CONTROLLER;
	    $route['action'] = self::DEFAULT_METHOD;

	    $segments = explode(self::URI_DELIMITER, $uri);

	    // Removing the first slashe
	    if (count($segments) != 0) {
		array_shift($segments);
	    }

	    if (isset($segments[2])) {
		$route['module'] = $segments[2];
	    }

	    if (isset($segments[3])) {
		$route['controller'] = $segments[3];
	    }

	    if (isset($segments[4])) {
		$route['action'] = $segments[4];
	    }
	}

	// Loading the contoller
	$controllerPath = $this->controllerPath . DIRECTORY_SEPARATOR .
		$route['module'] . DIRECTORY_SEPARATOR .
		$route['controller'] . self::CONTROLLER_EXT;
	if (!file_exists($controllerPath))
	    $controllerPath = $this->controllerPath . DIRECTORY_SEPARATOR .
		    self::DEFAULT_MODULE . DIRECTORY_SEPARATOR .
		    $route['controller'] . self::CONTROLLER_EXT;

	require ($controllerPath);

	// Create the controller
	$controller = $route['controller'] . self::SUFFIX_OBJECT;
	$obj = new $controller($this);

	// Loading the action
	$method = $route['action'] . self::SUFFIX_METHOD;

	if (method_exists($obj, self::INIT_METHOD)) {
	    $obj->init();
	}

	$obj->$method();
    }

    public function addRoute($route) {
	$this->routes[] = $route;
    }

    public function getViewPath() {
	return $this->viewPath;
    }

    public function getControllerPath() {
	return $this->controllerPath;
    }

}