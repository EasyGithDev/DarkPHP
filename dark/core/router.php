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

    const DEFAULT_CONTROLLER = 'indexController';
    const DEFAULT_METHOD = 'indexAction';
    const URI_DELIMITER = '/';
    const CONTOLLER_EXT = '.php';
    const SUFFIX_METHOD = 'Action';

    protected static $instance;
    protected $app = NULL;
    protected $controllerPath = '';
    protected $viewPath = '';
    protected $controller = NULL;
    protected $view = NULL;

    public function __construct(Application $app) {
	$this->app = $app;
	$this->viewPath = $this->app->getApplicationPath() . DIRECTORY_SEPARATOR . 'php/view';
	$this->controllerPath = $this->app->getApplicationPath() . DIRECTORY_SEPARATOR . 'php/controller';
    }

    public function run() {
	$uri = $_SERVER['REQUEST_URI'];
	$segments = explode(self::URI_DELIMITER, $uri);
	if (count($segments) == 0) {
	    $controller = self::DEFAULT_CONTROLLER . self::CONTOLLER_EXT;
	} else {
	    // Removing the first sladshe
	    array_shift($segments);
	    $controller = $segments[2] . self::CONTOLLER_EXT;
	}

	// Loading the contoller
	require ($this->controllerPath . DIRECTORY_SEPARATOR . $controller);

	// Create the controller
	$controller = ucfirst($segments[2]) . 'Controller';
	$obj = new $controller($this);

	// Loading the action
	if (isset($segments[3])) {
	    $method = $segments[3] . self::SUFFIX_METHOD;
	} else {
	    $method = self::DEFAULT_METHOD;
	}

	$obj->$method();
    }

    public function getViewPath() {
	return $this->viewPath;
    }

    public function getControllerPath() {
	return $this->controllerPath;
    }

}