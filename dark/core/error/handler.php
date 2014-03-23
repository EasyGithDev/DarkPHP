<?php

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

namespace Dark\Core\Error;

/**
 * Description of ErrorHandler
 *
 * @author florent
 */
class Handler implements \SplSubject {

    /**
     * Ceci est le tableau qui va contenir tous les objets qui nous observent.
     */
    protected $observers = array();
    protected $errno;
    protected $errstr;
    protected $errfile;
    protected $errline;

    public static function create() {
	return new self;
    }

    public function register() {
	set_error_handler(array($this, 'trace'));
    }

    public function attach(\SplObserver $observer) {
	$this->observers[] = $observer;
	return $this;
    }

    public function detach(\SplObserver $observer) {
	if (is_int($key = array_search($observer, $this->observers, true))) {
	    unset($this->observers[$key]);
	}
    }

    public function notify() {
	foreach ($this->observers as $observer) {
	    $observer->update($this);
	}
    }

    public function trace($errno, $errstr, $errfile, $errline) {
	$this->errno = $errno;
	$this->errstr = $errstr;
	$this->errline = $errline;
	$this->errfile = $errfile;
	$this->notify();
    }

    public function getErrno() {
	return $this->errno;
    }

    public function getErrstr() {
	return $this->errstr;
    }

    public function getErrfile() {
	return $this->errfile;
    }

    public function getErrline() {
	return $this->errline;
    }

}