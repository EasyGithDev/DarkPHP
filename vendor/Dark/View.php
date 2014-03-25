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

class View {

    const DEFAULT_VIEW_EXT = '.tpl';

    protected $router = NULL;
    protected $values = array();

    public function __construct(Router $router) {
	$this->router = $router;
    }

    public function __set($key, $value) {
	$this->values[$key] = $value;
    }

    public function __get($key) {
	return $this->values[$key];
    }

    public function load($tpl) {
	$path = $this->router->getViewPath();
	extract($this->values);
	require $path . DIRECTORY_SEPARATOR . $tpl . self::DEFAULT_VIEW_EXT;
    }

}
