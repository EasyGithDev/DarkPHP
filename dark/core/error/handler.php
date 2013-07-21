<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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